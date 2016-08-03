<?php
namespace FastDFS;

class Connection
{
    protected $tracker;

    protected $storage;

    public function __construct()
    {
        $this->init();
    }

    public function __destruct()
    {
        fastdfs_tracker_close_all_connections();
    }

    private function init()
    {
        $this->tracker = fastdfs_tracker_get_connection();

        if (!fastdfs_active_test($this->tracker)) {
            throw new ConnectionException(fastdfs_get_last_error_info(), fastdfs_get_last_error_no());
        }

        $this->storage = fastdfs_tracker_query_storage_store();

        if (!$this->storage) {
            throw new ConnectionException(fastdfs_get_last_error_info(), fastdfs_get_last_error_no());
        }
    }

    public function getTracker()
    {
        return $this->tracker;
    }

    public function getStorage()
    {
        return $this->storage;
    }
}
