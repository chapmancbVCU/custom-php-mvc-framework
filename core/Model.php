<?php
namespace Core;

/**
 * Parent class for our models.  Takes functions from DB wrapper and extract 
 * functionality further to make operations easier to use and improve 
 * extendability
 */
class Model {
    protected $_db;
    public $id;
    protected $_modelName;
    protected $_softDelete = false;
    protected $_table;
    protected $_validates = true;
    protected $_validationErrors = [];

    /**
     * Default constructor.
     * 
     * @param string $table The name of the table so we can work with the 
     * correct child model class.
     */
    public function __construct($table) {
        $this->_db = DB::getInstance();
        $this->_table = $table;

        /* Replace table name under scores with a space and use ucwords upper 
           case each word of model and replaces all spaces with no space. 
           $table = 'user_sessions => User Sessions => UserSessions */
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', '', $this->_table)));
    }

    /**
     * Generates error messages that occur during form validation
     *
     * @param string $field The form field associated with failed form 
     * validation
     * @param string $message A message that describes to the user the cause 
     * for failed form validation.
     * @return void
     */
    public function addErrorMessage($field, $message) {
        $this->validates = false;
        $this->_validationErrors[$field] = $message;  
    }

    public function afterSave() {}

    /**
     * Take POST array and assign it to our object.  Sanitize values 
     * before saving.
     */
    public function assign($params) {
        if(!empty($params)) {
            foreach($params as $key => $val) {
                if(property_exists($this, $key)) {
                    $this->$key = $val;
                }
            }
            return true;
        }
        return false;
    }

    public function beforeSave() {}

    /**
     * Grab object and if we just need data for smaller result set.
     */
    public function data() {
        $data = new stdClass();
        foreach(Helper::getObjectProperties($this) as $column => $value) {
            $data->column = $value;
        }
        return $data;
    }

    /**
     * Wrapper for database delete function.  If not softDelete we set it.
     * If row is set to softDelete we call the database delete function.
     */
    public function delete($id = '') {
        if($id == '' && $this->id == '') return false;
        $id = ($id == '') ? $this->id : $id;
        if($this->_softDelete) {
            return $this->update($id, ['deleted' => 1]);
        }

        return $this->_db->delete($this->_table, $id);
    }

    /**
     * Gets columns from table.
     * 
     * @return
     */
    public function getColumns() {
        return $this->_db->getColumns($this->_table);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getErrorMessages() {
        return $this->_validationErrors;
    }

    /**
     * Wrapper for the find function that is found in the DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return bool|array An array of object returned from an SQL query.
     */
    public function find($params = []) {
        $params = $this->_softDeleteParams($params);
        $resultsQuery = $this->_db->find($this->_table, $params, get_class($this));
        if(!$resultsQuery) return [];
        return $resultsQuery;
    }

    /**
     * Get result from database by primary key ID.
     *
     * @param int $id The ID of the row we want to retrieve from the database.
     * @return object The row from a database.
     */
    public function findById($id) {
        return $this->findFirst(['conditions'=>"id = ?", 'bind' => [$id]]);
    }

    /**
     * Wrapper for the findFirst function that is found in the DB class.
     *
     * @param array $params The values for the query.  They are the fields of 
     * the table in our database.  The default value is an empty array.
     * @return bool|object An array of object returned from an SQL query.
     */
    public function findFirst($params = []) {
        $params = $this->_softDeleteParams($params);
        $resultQuery = $this->_db->findFirst($this->_table, $params, get_class($this));
        return $resultQuery;
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
        return $this->_db->insert($this->_table, $fields);
    }

    public function isNew() {
        return (property_exists($this, 'id') && !empty($this->id)) ? false : true;
    }
    
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
     * Wrapper for database delete function.
     */
    public function query($sql, $bind) {
        return $this->_db->query($sql, $bind);
    }

    public function runValidation($validator) {
        $key = $validator->field;
        if(!$validator->success) {
            $this->_validates = false;
            $this->_validationErrors[$key] = $validator->message;
        }
    }

    /**
     * Wrapper for update and insert functions.
     */
    public function save() {
        $this->validator();
        if($this->_validates) {
            $this->beforeSave();
            $fields = Helper::getObjectProperties($this);

            // Determine whether to update or insert.
            if(property_exists($this, 'id') && $this->id != '') {
                $save =  $this->update($this->id, $fields);
                $this->afterSave();
                return $save;
            } else {
                $save = $this->insert($fields);
                $this->afterSave();
                return $save;
            }
        }
        return false;
    }

    protected function _softDeleteParams($params) {
        if($this->_softDelete) {
            if(array_key_exists('conditions', $params)) {
                if(is_array($params['conditions'])) {
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

    public function update($id, $fields) {
        if(empty($fields) || $id == '') return false;
        return $this->_db->update($this->_table, $id, $fields);
    }

    public function validationPassed() {
        return $this->_validates;
    }

    public function validator() {

    }
}