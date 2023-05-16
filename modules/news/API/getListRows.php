<?php
 
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jun 20, 2010 8:59:32 PM
 */
 
namespace NukeViet\Module\News\Api;
 
use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
 
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}
 
class getListRows implements IApi
{
    private $result;
 
    /**
     * @return number
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }
 
    /**
     * @return string
     */
    public static function getCat()
    {
        return 'System';
    }
 
    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        
        $this->result = $result;
    }
 
    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $db,$nv_Request,$db_config,$global_config;

        $module_name = Api::getModuleInfo();
		$module_data = $module_name['module_data'];
        $page = 1;
		$per_page = 10;

		$sql = 'SELECT id, catid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating FROM '. NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status = 1 ORDER BY addtime DESC LIMIT 5 OFFSET '.($page - 1) * $per_page;
        try {
            $result = $db->query($sql);
            if (!empty($result)){
                $array_catpage = [];
                foreach($result as $row) {
                    $array_catpage[] = $row ;
                }
                $this->result->set('data', $array_catpage );
                $this->result->setMessage('OKE');
                $this->result->setSuccess();

            } else{
                $this->result->set('error', 'Dữ liệu trống!!!');
                $this->result->setError('Error');
                $this->result->setSuccess();
            }
        } 
        catch (Exception $e) {
            $this->result->set('error', $e);
            $this->result->setError('Error');
            $this->result->setSuccess();
        }
        
        return $this->result->getResult();
    }
}