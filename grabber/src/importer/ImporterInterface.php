<?php

namespace grabber\importer;

interface ImporterInterface {
    public function import() : ImporterInterface;
    public function run() : ImporterInterface;
}