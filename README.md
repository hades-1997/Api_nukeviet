
# Lưu ý khi sử dụng API
Nếu không bắt buộc không nên sử dụng API (Mặc định NukeViet < 4.5 không cung cấp API, bản NukeViet 4.5 trở đi sẽ không kích hoạt mặc định)

Tại chức năng Cấu hình ⇒ Cấu hình chung cần Bật Remote API

Quyền truy cập API tại phần Quản trị ⇒ Quyền truy cập API đã chọn đủ API Role có chứa API cần gọi.

Cấp quyền ít nhất: Một API Roles chỉ nên có các quyền ít nhất, không nên cấp tất cả các quyền. Quyền có thể được thêm vào khi cần thiết và nên được thu hồi khi không còn được sử dụng.

Quyền truy cập API, nên được giới hạn theo IP, Xóa Quyền truy cập API nếu không dùng nữa.

Sử dụng HTTPS cho cả website, trong đó có API

Nếu phải viết thêm API, hãy viết nó đơn giản: Mỗi khi bạn làm cho giải pháp phức tạp hơn một cách “không cần thiết”, bạn cũng có khả năng để lại một lỗ hổng

# Trong tài liệu này chúng tôi ví dụ module hiện tại là module news đang chuẩn bị lập trình chức năng API.

Lưu ý: Tên module, tên file Api và tên Class phân biệt chữ hoa và chữ thường.

Bước 1: Tạo thư mục Api để chứa các file Api

Trong thư mục của module tạo thêm thư mục Api. Khi đó tồn tại đường dẫn modules/News/Api

Bước 2: Tạo file trong thư mục Api

Mỗi Api sẽ nằm trong một file php nằm trong thư mục Api. Tên file là tên class (phân biệt chữ viết hoa và viết thường). Ví dụ:

CreatArticle.php
```
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
```

Giải thích code như sau:

# Namespaces
```
namespace NukeViet\Module\page\Api;
```
# Quy luật:
```
NukeViet\Module\<ModuleFile>\Api
```
* <ModuleFile> chính là tên thư mục chứa module, trong ví dụ này là page.

# Sau khi đặt namespace, ta khai báo sử dụng các class NukeViet Core hỗ trợ:
```
use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
  ```
# Class Name
  ```
class CreatArticle implements IApi
```
* Class cần phải implements lớp IApi, theo đó phải có đủ 3 phương thức

/**
 * Lấy được quyền hạn sử dụng của admin
 * Admin tối cao, điều hành chung hay quản lý module được sử dụng
 */
  ```
public static function getAdminLev();
 ```
/**
 * Danh mục, cũng là khóa ngôn ngữ của API
 * Nếu không có danh mục thì trả về chuỗi rỗng
 */
  ```
public static function getCat();
 ```
/**
 * Thiết lập trình xử lý kết quả
 *
 * @param ApiResult $result
 */
  ```
public function setResultHander(ApiResult $result);
 ```
/**
 * Thực thi API
 */
  ```
public function execute();
``` 
* Lưu ý: Class name chính là tên file. Ví dụ class name là CreatArticle thì file sẽ là module/Page/Api/CreatArticle.php
Phương thức getAdminLev

# Cho biết đối tượng được quyền sử dụng API:
```
public static function getAdminLev()
{
    return Api::ADMIN_LEV_MOD;
}
```  
Cần trả về một trong 3 giá trị:
```
Api::ADMIN_LEV_GOD; // 1: Admin tối cao
Api::ADMIN_LEV_SP; // 2: Điều hành chung
Api::ADMIN_LEV_MOD; // 3: Quản lý module
```
# Phương thức getCat

* Cho biết danh mục của API nếu có, nếu API không được xếp danh mục hãy trả về chuỗi rỗng.
```
public static function getCat()
{
    return 'System';
}
  ```
# Phương thức setResultHander
```
 public function setResultHander(ApiResult $result)
{
    $this->result = $result;
}
 ``` 
Giữ nguyên code như mẫu, không thay đổi thêm.

# Code thực thi Api
```
 public function execute()
{
    // Viết code thực thi tại đây
 
    return $this->result->getResult();
}
```  
Code thực thi được viết tự do, và bắt buộc phải trả về thông qua phương thức getResult của class NukeViet\Api\ApiResult

Sử dụng các dữ liệu hệ thống
  
* Lấy biến $module_name sử dụng lệnh $module_name = Api::getModuleName();
  
* Lấy biến $module_info sử dụng lệnh $module_name = Api::getModuleInfo();. 
  
* Các biến $module_data, $module_file, $module_upload xác định từ $module_info.
  
* Lấy ID của Admin thực hiện API sử dụng lệnh $admin_id = Api::getAdminName();
  
* Lấy Username của Admin thực hiện API sử dụng lệnh $admin_username = Api::getAdminId();
  
* Lấy Cấp bậc của Admin thực hiện API sử dụng lệnh $admin_level = Api::getAdminLev();
  
* Các biến hệ thống như $nv_Lang, $nv_Request, $db, $nv_Cache, … có thể gọi global như thông thường.
  
# Cách trả dữ liệu về
Message thông báo:
```
$this->result->setMessage($nv_Lang->getModule('empty_bodytext'));
```  
Mã lỗi (nếu có):
```
$this->result->setCode($code);
  ```
Đánh dấu thành công:
```
$this->result->setSuccess();
  ```
Đánh dấu lỗi:
```
$this->result->setError();
  ```
Các dữ liệu khác:
```
$this->result->set($key, $value);
  ```
# Bước 3 thêm giá trị api vào file ngôn ngữ của module
```
$lang_module['api_modulename']
$lang_module['api_modulename_class']
  ```
 * Ví dụ:
 ```
$lang_module['api_System_getListRows'] = 'Lấy ra tất cả tin tức';
$lang_module['api_System'] = 'Tin Tức';
```
 
# API của hệ thống

Tương tự như API của module ngoại trừ các điểm khác sau:
File API nằm trong thư mục includes/api
  ```
namespace là namespace NukeViet\Api;
  ```
# Cách gọi API nội bộ
Ta có thể dùng hàm nv_local_api, cụ thể như sau
```
$return = nv_local_api($cmd, $params, $adminidentity = '', $module = '');
  ```
Trong đó:

* $return: là kết quả API trả về
  
* $cmd: là tương ứng với action khi remote
  
* $params: là mảng data nó sẽ được chuyển thành biến $_POST
  
* $adminidentity: là userid hoặc username của admin
  
* $module: tương ứng với module khi remote
  
* Ví dụ: Với cách gọi qua CURL thông thường, ta cần phải làm các bước sau:
  
```
$agent = 'NukeViet Remote API Lib';
$safe_mode = (ini_get('safe_mode') == '1' || strtolower(ini_get('safe_mode')) == 'on') ? 1 : 0;
$open_basedir = ini_get('open_basedir') ? true : false;
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, '...'//URL của API);
curl_setopt($ch, CURLOPT_HEADER, 0);
 
if (!$safe_mode and !$open_basedir) {
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
     curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
}
 
$params= [
    'apikey' => '...', // Khóa được cung cấp
    'timestamp' => '...', // Thời gian resquest sai lệch với giờ máy chủ không quá 5 giây
    'hashsecret' => '...', // Mã bí mật mỗi lần resquest = password_hash($apisecret . '_' . $timestamp, PASSWORD_DEFAULT);
    'action' => 'GetUsername', // Tên API hoặc action khi remote
    'module' => 'user', // Module xử lý, ở đây là user, để trống thì là API của hệ thống
    'language' => 'vi' // Bắt buộc nếu API của module trên site đa ngôn ngữ
 
    //Các dữ liệu cần thiết, ví dụ ở đây ta sẽ truyền userid
    'userid' => $userid
];
$str = http_build_query($request);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_POST, sizeof($request));
curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
$return = curl_exec($ch);  //Dữ liệu API sẽ trả về
curl_close($ch);
  
 ```
Với hàm nv_local_api, ta sử dụng:
  
```
$params= [
     'userid' => $userid
];
$return = nv_local_api('GetUsername', $params, 'admin', 'user');
```

 Với:
  * $return: Dữ liệu API sẽ trả về
  * 'GetUsername': Tên API hoặc action khi remote
  * $params: Mảng dữ liệu truyền vào API
  * 'admin': username của tài khoản admin
  * 'user': Module xử lý

Có thể xem thêm về hàm nv_local_api tại: …/includes/function.php
  
 # Cấu hình chạy post main
 * Headers 
  ```
  Accept : application/json
  Content-Type : application/json
  
  ```
  # Cấu hình body form-data
  ```
  apikey : 
  hashsecret : 
  timestamp : 
  action : 
  module : 
  language : 
  ```
  # Ẩn timestamp trên file api.php 
  ```
  // if ($api_credential['timestamp'] + 5 < NV_CURRENTTIME or $api_credential['timestamp'] - 5 > NV_CURRENTTIME) {
    ////   Sai lệch thời gian hơn 5 giây
    // $apiresults->setCode(ApiResult::CODE_MISSING_TIME)
        // ->setMessage('Incorrect API time: ' . date('H:i:s d/m/Y', $api_credential['timestamp']) . ', Server time: ' . date('H:i:s d/m/Y', NV_CURRENTTIME))
        // ->returnResult();
// }

  ```
  # Tắt chế độ bảo mật 
  ![image](https://user-images.githubusercontent.com/70182883/233282281-59b20b19-c2e4-4e5a-8e51-a9ea2bae5138.png)
