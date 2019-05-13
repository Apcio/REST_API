<?php

interface ICREATE {
    public function createData(stdClass $data): stdClass;
}

interface IDELETE {
    public function deleteData(stdClass $data): stdClass;
}

interface IREAD {
    public function readData(array $params): stdClass;
}

interface IUPDATE {
    public function updateData(stdClass $data): stdclass;
}