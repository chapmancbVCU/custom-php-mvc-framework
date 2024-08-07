<?php
namespace Core;
use Core\Helper;
use \stdClass;

/**
 * Parent class for our models.  Takes functions from DB wrapper and extract 
 * functionality further to make operations easier to use and improve 
 * extendability.
 */
class Model {
    protected static $_db;
    public $id;
    protected $_modelName;
    protected static $_softDelete = false;
    protected static $_table;
    protected $_validates = true;
    protected $_validationErrors = [];

    /**
     * Default constructor.
     * 
     * @param string $table The name of the table so we can work with the 
     * correct child model class.
     */
    public function __construct() {
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_',' ', static::$_table)));
        $this->onConstruct();
    }

    /**
     * Generates error messages that occur during form validation.
     *
     * @param string $field The form field associated with failed form 
     * validation
     * @param string $message A message that describes to the user the cause 
     * for failed form validation.
     * @return void
     */
    public function addErrorMessage($field,$message){
        $this->_validates = false;
        if(array_key_exists($field,$this->_validationErrors)){
            $this->_validationErrors[$field] .= " " . $message;
        } else {
            $this->_validationErrors[$field] = $message;
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function afterDelete() {}

    /**
     * Called before save.
     *
     * @return void
     */
    public function afterSave() {}

    /**
     * Update the object with an associative array.
     * 
     * @param array Take values from post array and assign values.
     * @return bool Report for whether or not the operation was successful.
     */
    public function assign($params, $list = [], $blackList = true) {
        foreach($params as $key => $val) {
            // check if there is permission to update the object
            $whiteListed = true;
            if(sizeof($list) > 0){
              if($blackList){
                    $whiteListed = !in_array($key,$list);
              } else {
                    $whiteListed = in_array($key,$list);
              }
            }
            if(property_exists($this,$key) && $whiteListed){
                $this->$key = $val;
            }
        }
        return $this;
    }

    /**
     * This runs before save needs to return a boolean
     *
     * @return boolean 
     */
    public function beforeDelete() { return true; }
    
    /**
     * Called after save.
     *
     * @return void
     */
    public function beforeSave() {}

    /**
     * Grab object and if we just need data for smaller result set.
     * 
     * @return object The data associated with an object.
     */
    public function data() {
        $data = new \stdClass();
        foreach(static::getColumns() as $column) {
            $columnName = $column->Field;
            $data->{$columnName} = $this->{$columnName};
        }
        return $data;
    }

    /**
     * Wrapper for database delete function.  If not softDelete we set it.
     * If row is set to softDelete we call the database delete function.
     * 
     * @param string $id The primary key for the record we want to remove from a 
     * database table.  The default value is an empty string.
     * @return bool True if delete operation is successful.  Otherwise, we 
     * return false.
     */
    public function delete() {
        if($this->id == '' || !isset($this->id)) return false;
        $this->beforeDelete();
        if(static::$_softDelete) {
            $deleted = $this->update(['deleted' => 1]);
        } else {
            $deleted = static::getDb()->delete(static::$_table, $this->id);
        }
        $this->afterDelete();
        return $deleted;
    }

    /**
     * Gets columns from table.
     * 
     * @return array An array of objects where each one represents a column 
     * from a database table.
     */
    public static function getColumns() {
        return static::getDb()->getColumns(static::$_table);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getColumnsForSave() {
        $columns = static::getColumns();
        $fields = [];
        foreach($columns as $column) {
            $key = $column->Field;
            $fields[$key] = $this->{$key};
        }
        return $fields;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function getDb(){
        if(!self::$_db) {
            self::$_db = DB::getInstance();
        }
        return self::$_db;
    }

    /**
     * Displays error messages when form validation fails.
     *
     * @return array An array that contains a list of items that failed form 
     * validation.
     */
    public function getErrorMessages() {
        return $this->_validationErrors;
    }

    /**
     * Undocumented function
     *
     * @param [type] $params
     * @return void
     */
    protected static function _fetchStyleParams($params){
        if(!isset($params['fetchStyle'])) {
            $params['fetchStyle'] = \PDO::FETCH_CLASS;
        }
        return $params;
    }

    /**
     * Wrapper for the find function that is found in the DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return bool|array An array of object returned from an SQL query.
     */
    public static function find($params = []) {
        $params = static::_fetchStyleParams($params);
        $params = static::_softDeleteParams($params);
        $resultsQuery = static::getDb()->find(static::$_table, $params, static::class);
        if(!$resultsQuery) return [];
        return $resultsQuery;
    }

    /**
     * Get result from database by primary key ID.
     *
     * @param int $id The ID of the row we want to retrieve from the database.
     * @return bool|object The row from a database.
     */
    public static function findById($id) {
        return static::findFirst(['conditions'=>"id = ?", 'bind' => [$id]]);
    }

    /**
     * Wrapper for the findFirst function that is found in the DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return bool|object An array of object returned from an SQL query.
     */
    public static function findFirst($params = []) {
        $params = static::_fetchStyleParams($params);
        $params = static::_softDeleteParams($params);
        $resultQuery = static::getDb()->findFirst(static::$_table, $params,static::class);
        return $resultQuery;
    }

    public static function findTotal($params=[]) {
        $params = static::_fetchStyleParams($params);
        $params = static::_softDeleteParams($params);
        unset($params['limit']);
        unset($params['offset']);
        return static::getDb()->findTotal(static::$_table, $params);
    }

    /** 
     * Wrapper for database insert function.
     * 
     * @param array $fields The field names and the respective values we will 
     * use to populate a database record.  The default value is an empty array.
     * @return bool Report for whether or not the operation was successful.
     */
    public function insert($fields) {
        if(empty($fields)) return false;
        if(array_key_exists('id', $fields)) unset($fields['id']);
        return static::getDb()->insert(static::$_table, $fields);
    }

    /**
     * Checks if an object is a new insertion or an existing record.
     *
     * @return bool Returns true if the record exists.  Otherwise, we 
     * return false.
     */
    public function isNew() {
        return (property_exists($this, 'id') && !empty($this->id)) ? false : true;
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function onConstruct(){}
    
    /**
     * Populates object with data.
     *
     * @param array|object $result Results from a database query.
     * @return void
     */
    protected function populateObjData($result) {
        foreach($result as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Processes, validates, and uploads a file submitted through a post 
     * request.  You can also test if the file is of a valid type using the 
     * optional $fileTypes array.  The optional $oldFile argument allows you 
     * to delete the previous file by giving it the name stored in the 
     * database.
     *
     * @param array $file An associate array containing information about 
     * file received during POST.
     * @param string $imageName The name for type of image to match image's 
     * parent directory.  For example, we used profileImage for all profile 
     * images.
     * @param string $arg Additional information that can be used as part of 
     * uploaded file's name.  The default value is an empty string.  Providing 
     * an empty string will cause whitespace within the filename to be 
     * removed before upload.
     * @param string $oldFile The name of the old file we want to remove.  The 
     * default value is an empty string.
     * @param string $parentDir The directory under public that contains 
     * collections of media.
     * @param array $fileTypes The file types we will test to determine if 
     * it is a valid file type.
     * @return string $targetFile The filename and extension so name can be 
     * stored in a database.
     */
    public function processFile(array $file, 
        string $imageName, 
        string $arg = "", 
        string $oldFile = "",
        string $parentDir= "",
        array $fileTypes = []
        ): string {
            
        if($file[$imageName]['name'] != "") {
            // Process and upload file.
            $target_dir = getcwd().DS."public". DS . $parentDir . DS . $imageName . DS;
            $imageFileType = strtolower(pathinfo($file[$imageName]["name"],PATHINFO_EXTENSION));
            if(strcmp($arg, "") == 0) {
                $target_file = preg_replace('/\s+/u', '', basename($file[$imageName]["name"]));
            } else {    
                $target_file = $arg . "." .$imageFileType;
            }
            if($file[$imageName]["size"] > MAX_FILE_UPLOAD_SIZE) {
                $this->addErrorMessage($imageName, "File is too large.");
            }
            if(!empty($fileTypes) && !in_array($imageFileType, $fileTypes)) {
                $this->addErrorMessage($imageName, "Invalid file type.");
            }
            
            // Remove file only if old file name is provided.
            self::unlink($imageName, $oldFile);

            // Check for validation failures and upload the file.
            $full_target_path = $target_dir . $target_file;
            if($this->_validates && !move_uploaded_file($file[$imageName]["tmp_name"], $full_target_path)) {
                $this->addErrorMessage($imageName, "File upload failure.");
            } 
        } else {
            // If no file selected we remove profile image.
            $target_file = "";
            self::unlink($imageName, $oldFile);
        } 
        return $target_file;
    }

    /**
     * Wrapper for database query function.
     * 
     * @param string $sql The database query we will submit to the database.
     * @param array The values we want to bind in our database query.
     * @return DB The results of the database query.
     */
    public function query($sql, $bind=[]) {
        return static::getDb()->query($sql, $bind);
    }

    /**
     * Runs a validator object and sets validates boolean and adds error 
     * message if validator fails.
     *
     * @param object $validator The validator object.
     * @return void
     */
    public function runValidation($validator) {
        // $validator->field is the field we ar validating.
        $key = $validator->field;
        if(!$validator->success){
            $this->addErrorMessage($key,$validator->msg);
        }
    }

    /**
     * Wrapper for update and insert functions.  A failed form validation will
     * cause this function to return false.
     * 
     * @return bool True if the update operation is successful.  Otherwise, 
     * we return false.
     */
    public function save() {
        $this->validator();
        $save = false;
        if($this->_validates){
            $this->beforeSave();
            $fields = $this->getColumnsForSave();
            // determine whether to update or insert
            if($this->isNew()) {
                $save = $this->insert($fields);
                // populate object with the id
                if($save){
                    $this->id = static::getDb()->lastID();
                }
            } else {
                $save = $this->update($fields);
            }
            // run after save
            if($save){
                $this->afterSave();
            }
        }
        return $save;
    }

    /**
     * Adds to the conditions to avoid getting soft deleted rows returned
     *
     * @param array $params Defined parameters to search by.
     * @return array $params parameters with appended conditions for soft 
     * delete.
     */
    protected static function _softDeleteParams($params){
        if(isset($params['includeDeleted']) && $params['includeDeleted'] == true) return $params;
        if(static::$_softDelete){
            if(array_key_exists('conditions',$params)){
                if(is_array($params['conditions'])){
                    $params['conditions'][] = "deleted != 1";
                } else {
                    $params['conditions'] .= " AND deleted != 1";
                }
            } else {
                $params['conditions'] = "deleted != 1";
            }
        }
        return $params;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function timeStamps() {
        $dt = new \DateTime("now", new \DateTimeZone("UTC"));
        $now = $dt->format('Y-m-d H:i:s');
        $this->updated_at = $now;
        if($this->isNew()) {
            $this->created_at = $now;
        }
    }

    /**
     * Removes a file during a remove/update file operation.  If there is a 
     * failure a failed to remove previous file message is set.
     *
     * @param string $imageName The name for type of image to match image's 
     * parent directory.  For example, we used profileImage for all profile 
     * images.
     * @param string $oldFile The name of the old file we want to remove.  The 
     * default value is an empty string.
     * @return void
     */
    private function unlink($imageName, $oldFile = "") {
        if(!unlink(getcwd().DS."public". DS ."images" . DS . $imageName . DS .$oldFile) && strcmp($oldFile, "")) {
            $this->addErrorMessage($imageName, "Failed to remove previous file.");
        }
    }

    /**
     * Wrapper for the update function found in the DB class.
     *
     * @param int $id The primary key for the record we want to remove from a 
     * database table.
     * @param array $fields The value of the fields we want to set for the 
     * database record.  The default value is an empty array.
     * @return bool True if the update operation is successful.  Otherwise, 
     * we return false.
     */
    public function update($fields) {
        if(empty($fields) || $this->id == '') return false;
        return static::getDb()->update(static::$_table, $this->id, $fields);
    }

    /**
     * Getter function for the $_validates boolean instance variable.
     *
     * @return bool $_validates is true if validation is successful and 
     * false if there is a failure.
     */
    public function validationPassed() {
        return $this->_validates;
    }

    /**
     * Function that is called on save.  If validation fails the save function 
     * will not proceed.  This function is just a signature and must be 
     * implemented by models that run form validation because since it is 
     * called from within this class.
     * @method validator
     * @return void
     */
    public function validator() {

    }
}