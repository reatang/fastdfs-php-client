<?php
namespace FastDFS;

class FileSystem
{
    /**
     * 
     * @var Resource
     */
    protected $connection;

    public function __construct(Connection $connection = null)
    {
        if ($connection == null) {
            $this->connection = new Connection;
        } else {
            $this->connection = $connection;
        }

    }

    /**
     * 客户的版本号
     * 
     * @return string
     */
    public static function version()
    {
        return fastdfs_client_version();
    }

    /**
     * 错误号
     * @return [type] [description]
     */
    public function errorNo()
    {
        return fastdfs_get_last_error_no();
    }

    /**
     * 错误信息
     * 
     * @return [type] [description]
     */
    public function errorMsg()
    {
        return fastdfs_get_last_error_info();
    }

    /**
     * 获取上传文件的信息
     * 
     * @param  string $file_full_name 文件名 | 文件ID
     * @param  string $group_name 组名
     * 
     * @return array 
     */
    public function fileInfo($file_name, $group_name = null)
    {
        if (is_null($group_name)) {
            return fastdfs_get_file_info1($file_name);
        } else {
            return fastdfs_get_file_info($group_name, $file_name);
        }
    }

    /**
     * 上传本地文件
     * Upload local file
     * 
     * @param  string $local_file_name 本地文件
     * @param  array  $meta_list       扩展信息
     * 
     * @return array
     */
    public function put($local_file_name, $meta_list = array())
    {
        if (isset($meta_list['group_name'])) {
            $group_name = $meta_list['group_name'];
            unset($meta_list['group_name']);
        } else {
            $group_name = null;
        }

        $upload_file_info = fastdfs_storage_upload_by_filename(
                $local_file_name, null, 
                $meta_list, $group_name, 
                $this->connection->getTracker(), $this->connection->getStorage()
            );

        return $upload_file_info;
    }

    /**
     * 上传从文件
     * 
     * @param  string $local_file_name  本地文件
     * @param  string $master_file_name 主文件ID / 主文件名
     * @param  string $suffixes         前缀
     * @param  string|null $group_name       组名
     * 
     * @return array
     */
    public function slavePut($local_file_name, $master_file_name, $suffixes = '_sub', $group_name = null)
    {
        list($group_name, $file_name) = $this->parseFileId($master_file_name, $group_name);

        $sub_file_name_info = fastdfs_storage_upload_slave_by_filename(
                $local_file_name, $group_name,
                $file_name, $suffixes
            );

        return $sub_file_name_info;
    }

    public function append()
    {

    }

    /**
     * 删除文件
     * @return [type] [description]
     */
    public function delete($file_id, $group_name = null)
    {
        list($group_name, $file_name) = $this->parseFileId($file_id, $group_name);

        return fastdfs_storage_delete_file($group_name, $filename);;
    }

    /**
     * 下载文件
     * 
     * @param  string      $file_id       主文件ID / 主文件名
     * @param  string|null $group_name    组名
     * @param  string      $download_name 下载文件名
     * 
     * @return bool
     */
    public function download($file_id, $group_name = null, $download_name = null, $return = false)
    {
        list($group_name, $file_name) = $this->parseFileId($file_id, $group_name);

        if (is_null($download_name)) {
            $download_name = pathinfo($file_name, PATHINFO_BASENAME);
        }

        if ($return) {
            return fastdfs_storage_download_file_to_buff($group_name, $file_name, $download_name);
        } else {
            return fastdfs_storage_download_file_to_file($group_name, $file_name, $download_name);
        }
    }

    /**
     * 解析 FileId
     * 
     * @param  string $file_id 文件的ID 
     * @return array
     */
    protected function parseFileId($file_id, $group_name = null)
    {
        if (is_null($group_name)) {
            $group_name = strstr($file_id, '/', true);
            $file_name = strstr($file_id, '/');
        } else {
            $file_name = $file_id;
        }

        return [$group_name, $file_name];
    }

    public function setMeta()
    {

    }

    public function getMeta()
    {

    }
}