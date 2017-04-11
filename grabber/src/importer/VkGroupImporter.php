<?php

namespace grabber\importer;

use grabber\models\Supplier;
use grabber\models\Vacancy;
use vaseninm\configure\Configure;

class VkGroupImporter extends AbstractImporter implements ImporterInterface
{
    const NAMESPACE = 'vk';
    const POST_COUNT = 10;
    private $vk = null;

    /**
     * @return \Vk
     */
    protected function getVk() : \Vk
    {
        if ($this->vk === null) {
            $this->vk = new \Vk(Configure::me()->get('vk'));
        }
        
        return $this->vk;
    }

    /**
     * @return $this
     */
    public function import() : ImporterInterface
    {
        /**
         * @var Supplier $supplier
         */
        $supplier = Supplier::find([
            'namespace' => self::NAMESPACE,
            'enabled' => true,
        ])
            ->sortBy(function (Supplier $supplier) {return $supplier->lastImport;}, true)
            ->first();

        if (! $supplier) {
            $this->log("Supplier from namespace [" . self::NAMESPACE . "] not found. Import skipped.");
            return $this;
        }

        $this->log("Supplier [" . $supplier->title . "] selected for this import.");

        $this->importOne($supplier);

        return $this;
    }

    /**
     * @param Supplier $supplier
     * @return $this
     */
    public function importOne(Supplier $supplier) : ImporterInterface
    {

        try {
            $posts = $this->getVk()->api('wall.get', [
                'domain' => $supplier->title,
                'count' => self::POST_COUNT,
                'filter' => 'owners',
                'extended' => 1,
                'fields' => 'signer'
            ])['items'];

            $supplier->lastImport = new \MongoTimestamp();
            $supplier->save();

            foreach ($posts as $post) {
                if ($post['is_pinned']) continue;

                $vacancy = Vacancy::one([
                    'id' => $post['id'],
                    'supplier.$id' => $supplier->_id,
                ]);

                if ($vacancy) continue;

                $vacancy = new Vacancy();
                $vacancy->id = $post['id'];
                $vacancy->text = $post['text'];
                $vacancy->status = Vacancy::STATUS_NEW;
                $vacancy->importedAt = new \MongoTimestamp();
                $vacancy->ownerId = $post['signer_id'];
                $vacancy->supplier = $supplier;

                if (array_key_exists('attachments', $post)) {
                    foreach ($post['attachments'] as $attach) {
                        if ($attach['type'] === 'photo') {
                            $vacancy->photo = $attach['photo']['photo_1280'];
                            break;
                        }
                    }
                }

                if ($vacancy->save()) {
                    $this->log("Add post id [{$vacancy->id}] from supplier [{$supplier->title}]");
                    $this->gearmanClient->doBackground('vacancy_create', $vacancy->getId());
                }
            }
        } catch (\VkException $e) {
            $this->log("Vk service is not available");
        }

        return $this;
    }
}