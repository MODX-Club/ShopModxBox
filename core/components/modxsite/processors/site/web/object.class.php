<?php

class modSiteWebObjectProcessor extends modObjectProcessor{
    
    public $classKey = 'modResource';
    
    public $checkSavePermission = true;
    /** @var string $beforeSaveEvent The name of the event to fire before saving */
    public $beforeSaveEvent = '';
    /** @var string $afterSaveEvent The name of the event to fire after saving */
    public $afterSaveEvent = '';
    
    
    # public function __construct(modX & $modx,array $properties = array()){
    #     
    #     print "sdf\n";
    #     
    #     return parent::__construct( $modx, $properties );
    # }
    
    
    public function initialize() {
        
        $this->setDefaultProperties(array(
            "new_object"   => false,        // Флаг, что это новый объект
            "save_object"   => false,       // Флаг, что объект надо сохранять
        ));
        
        $initialized = $this->initializeObject();
        
        if($initialized !== true){
            return $initialized;
        }
        
        $this->prepareObject($this->object);

        if ($this->getProperty('save_object') && $this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }
    
    
    protected function initializeObject(){
        
        if($this->getProperty('new_object')){
            $this->object = $this->newObject($this->classKey);
        }
        else{
            $primaryKey = $this->getProperty($this->primaryKeyField,false);
            if (empty($primaryKey)){
                return $this->modx->lexicon($this->objectType.'_err_ns');
            }
            $this->object = $this->getObject($this->classKey,$primaryKey);
        }
        
        if (empty($this->object)){
            return $this->modx->lexicon($this->objectType.'_err_nfs');
        }
        
        // else
        return true;
    }
    
    
    protected function prepareObject(& $object){
        
        return $object;
    }
    
    
    public function run($method = '') {
        if (!$this->checkPermissions()) {
            $o = $this->failure($this->modx->lexicon('permission_denied'));
        } else {
            $topics = $this->getLanguageTopics();
            foreach ($topics as $topic) {
                $this->modx->lexicon->load($topic);
            }

            $initialized = $this->initialize();
            if ($initialized !== true) {
                $o = $this->failure($initialized);
            } else {
                
                /* Run the beforeSet method before setting the fields, and allow stoppage */
                $beforeSet = $this->beforeSet();
                if ($beforeSet !== true) {
                    $o = $this->failure($beforeSet);
                }
                
                else{
                    $this->object->fromArray($this->getProperties());
                    
                    /* Run the beforeSave method and allow stoppage */
                    $canSave = $this->beforeSave();
                    
                    if ($canSave !== true) {
                        $o = $this->failure($canSave);
                    }
                    else{
                
                        /* run object validation */
                        if (!$this->object->validate()) {
                            /** @var modValidator $validator */
                            $validator = $this->object->getValidator();
                            if ($validator->hasMessages()) {
                                foreach ($validator->getMessages() as $message) {
                                    $this->addFieldError($message['field'],$this->modx->lexicon($message['message']));
                                }
                            }
                            
                            $o = $this->failure('');
                        }
                        else{
                            
                            if($method AND in_array($method, get_class_methods(__CLASS__))){
                                
                                $params = & func_get_args();
                                
                                array_shift($params);
                                
                                $o = call_user_func_array(array(__CLASS__, $method), $params);
                            }
                            else{
                                $o = $this->process();
                            }
                        }
                    }
                }
            }
        }
        $response = new modProcessorResponse($this->modx,$o);
        return $response;
    }
    
    
    public function process() {
#         /* Run the beforeSet method before setting the fields, and allow stoppage */
#         $canSave = $this->beforeSet();
#         if ($canSave !== true) {
#             return $this->failure($canSave);
#         }
# 
#         $this->object->fromArray($this->getProperties());

#         /* Run the beforeSave method and allow stoppage */
#         $canSave = $this->beforeSave();
#         if ($canSave !== true) {
#             return $this->failure($canSave);
#         }
# 
#         /* run object validation */
#         if (!$this->object->validate()) {
#             /** @var modValidator $validator */
#             $validator = $this->object->getValidator();
#             if ($validator->hasMessages()) {
#                 foreach ($validator->getMessages() as $message) {
#                     $this->addFieldError($message['field'],$this->modx->lexicon($message['message']));
#                 }
#             }
#         }

        /* run the before save event and allow stoppage */
#         $preventSave = $this->fireBeforeSaveEvent();
#         if (!empty($preventSave)) {
#             return $this->failure($preventSave);
#         }
# 
#         if($this->getProperty('save_object')){
#             if ($this->saveObject() == false) {
#                 return $this->failure($this->modx->lexicon($this->objectType.'_err_save'));
#             }
#         }
#         
#         $this->afterSave();
#         $this->fireAfterSaveEvent();
#         $this->logManagerAction();

        if($this->getProperty('save_object')){
    
            return $this->_SaveObject();
        }
        
        // else
        return $this->success('', $this->object);
    }
    
    /**
     * Abstract the saving of the object out to allow for transient and non-persistent object updating in derivative
     * classes
     * @return boolean
     */
    public function saveObject() {
        return $this->object->save();
    }

    /**
     * Override in your derivative class to do functionality before the fields are set on the object
     * @return boolean
     */
    public function beforeSet() { return !$this->hasErrors(); }


    /**
     * Override in your derivative class to do functionality before save() is run
     * @return boolean
     */
    public function beforeSave() { return !$this->hasErrors(); }

    /**
     * Override in your derivative class to do functionality after save() is run
     * @return boolean
     */
    public function afterSave() { return true; }
 


    /**
     * Fire before save event. Return true to prevent saving.
     * @return boolean
     */
    public function fireBeforeSaveEvent() {
        $preventSave = false;
        if (!empty($this->beforeSaveEvent)) {
            /** @var boolean|array $OnBeforeFormSave */
            $OnBeforeFormSave = $this->modx->invokeEvent($this->beforeSaveEvent,array(
                'mode'  => modSystemEvent::MODE_UPD,
                'data' => $this->object->toArray(),
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->objectType => &$this->object,
                'object' => &$this->object,
            ));
            if (is_array($OnBeforeFormSave)) {
                $preventSave = false;
                foreach ($OnBeforeFormSave as $msg) {
                    if (!empty($msg)) {
                        $preventSave .= $msg."\n";
                    }
                }
            } else {
                $preventSave = $OnBeforeFormSave;
            }
        }
        return $preventSave;
    }

    /**
     * Fire the after save event
     * @return void
     */
    public function fireAfterSaveEvent() {
        if (!empty($this->afterSaveEvent)) {
            $this->modx->invokeEvent($this->afterSaveEvent,array(
                'mode' => modSystemEvent::MODE_UPD,
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->objectType => &$this->object,
                'object' => &$this->object,
            ));
        }
    }

    /**
     * Log the removal manager action
     * @return void
     */
    public function logManagerAction() {
        $this->modx->logManagerAction($this->objectType.'_update',$this->classKey,$this->object->get($this->primaryKeyField));
    }

    /**
     * @param array $criteria
     * @return int
     */
    public function doesAlreadyExist(array $criteria) {
        return $this->modx->getCount($this->classKey,$criteria);
    }
    
    
    public function newObject($classKey){
        return $this->modx->newObject($classKey);
    }
    
    public function getObject($classKey, $primaryKey){
        return $this->modx->getObject($classKey, $primaryKey);
    }
    
    
    protected function _SaveObject(){
        $preventSave = $this->fireBeforeSaveEvent();
        if (!empty($preventSave)) {
            return $this->failure($preventSave);
        }
        if ($this->saveObject() == false) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_save'));
        }
        $this->afterSave();
        $this->fireAfterSaveEvent();
        $this->logManagerAction();
        
        return $this->cleanup();
    }
    
    public function cleanup() {
        return $this->success('Объект успешно сохранен', $this->object);
    }
    
    
    # public function beforeSave() {
    #     return "sdfsdf";
    # }
    
    
    # public function create(array $data = array()){
    #     
    #     # print "sdfsdf";
    #     # 
    #     # die('sdfs');
    #     return $this->run('_CreateObject', $data);
    # }
    # 
    # 
    # protected function _CreateObject(array $data = array()){
    #     
    #     $object = $this->modx->newObject($this->classKey, $data);
    #     
    #     # print_r($object->toArray());
    #     
    #     if($object->save()){
    #         
    #         return $this->success('', $object);
    #     }
    #     // else
    #     
    #     return $this->failure('');
    # }
    
    
    
    
    # public function remove($criteria= null){
    #     
    #     # print "sdfsdf";
    #     # 
    #     # die('sdfs');
    #     return $this->run('_RemoveObject', $criteria);
    # }
    # 
    # 
    # protected function _RemoveObject($criteria= null){
    #     
    #     $object = $this->modx->newObject($this->classKey, $data);
    #     
    #     # print_r($object->toArray());
    #     
    #     if($object->save()){
    #         
    #         return $this->success('', $object);
    #     }
    #     // else
    #     
    #     return $this->failure('');
    # }
    
}

return 'modSiteWebObjectProcessor';
