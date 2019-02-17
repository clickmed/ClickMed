<?php
class ManagerBlock extends ObjectModel
{
    /** @var string Name */
    public $description;
    public $title;
    public $hook_position;
    public $name_module;
    public $hook_module;
    public $position;
    public $active;
    public $insert_module;
    public $showhook;
    public $identify;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'dor_managerblock',
        'multishop' => true,
		'multilang' => true,
        'primary' => 'id_dor_managerblock',
        'fields' => array(
            'position' =>           array('type' => self::TYPE_INT,'lang' => false),
            'active' =>           array('type' => self::TYPE_INT,'lang' => false),
            'insert_module' =>           array('type' => self::TYPE_INT,'lang' => false),
            'showhook' =>           array('type' => self::TYPE_INT,'lang' => false),
			'identify' =>          array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
            'hook_position' =>          array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isGenericName', 'required' => false, 'size' => 128),
            'name_module' =>          array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isGenericName', 'required' => false, 'size' => 128),
            'hook_module' =>          array('type' => self::TYPE_STRING, 'lang' => false, 'validate' => 'isGenericName', 'required' => false, 'size' => 128),
            'title' =>          array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 128),
			'description' =>            array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString', 'size' => 9999),
        ),
    );
    
    public  function getStaticblockLists($id_shop = NULL, $hook_position= 'top') { 
        if (!Combination::isFeatureActive())
			return array();
		$id_lang = (int)Context::getContext()->language->id;
		$object =  Db::getInstance()->executeS('
                    SELECT * FROM '._DB_PREFIX_.'dor_managerblock AS psb 
					LEFT JOIN '._DB_PREFIX_.'dor_managerblock_lang AS psl ON psb.id_dor_managerblock = psl.id_dor_managerblock
                    LEFT JOIN '._DB_PREFIX_.'dor_managerblock_shop AS pss ON psb.id_dor_managerblock = pss.id_dor_managerblock
                    WHERE id_shop ='.$id_shop.' 
						AND id_lang ='.$id_lang.'
						AND `hook_position` = "'.$hook_position.'" 
						AND `showhook` = 1 ORDER BY `position` ASC
		');
                $newObject = array();
                if(count($newObject>0)) {
		    $blockModule= null;
		    $linkRedirect = isset($_SERVER['REDIRECT_REWRITEBASE'])?_PS_BASE_URL_.$_SERVER['REDIRECT_REWRITEBASE']:$_SERVER['SERVER_NAME'];
		    $urlReplace = $linkRedirect;
				foreach($object as $key=>$ob) {
					$nameModule = $ob['name_module'];
					$hookModule = $ob['hook_module'];
					$insert_module = $ob['insert_module'];
					if($insert_module!=0){
						$blockModule = $this->getModuleAssign($nameModule, $hookModule);
					}
					$ob['block_module'] = $blockModule;
					$description = $ob['description'];
					$description = str_replace('dor_maxshop/img/',$urlReplace."img/",$description);
					$ob['description'] = $description;
					$newObject[$key] = $ob;
				}
                  return $newObject;

                }
                return null;
                
    }

   
    public function updatePosition($way, $position)
    {
        if (!$res = Db::getInstance()->executeS('
			SELECT `id_carrier`, `position`
			FROM `'._DB_PREFIX_.'carrier`
			WHERE `deleted` = 0
			ORDER BY `position` ASC'
        ))
            return false;

        foreach ($res as $carrier)
            if ((int)$carrier['id_carrier'] == (int)$this->id)
                $moved_carrier = $carrier;

        if (!isset($moved_carrier) || !isset($position))
            return false;

        // < and > statements rather than BETWEEN operator
        // since BETWEEN is treated differently according to databases
        return (Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'carrier`
			SET `position`= `position` '.($way ? '- 1' : '+ 1').'
			WHERE `position`
			'.($way
                    ? '> '.(int)$moved_carrier['position'].' AND `position` <= '.(int)$position
                    : '< '.(int)$moved_carrier['position'].' AND `position` >= '.(int)$position.'
			AND `deleted` = 0'))
            && Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'carrier`
			SET `position` = '.(int)$position.'
			WHERE `id_carrier` = '.(int)$moved_carrier['id_carrier']));
    }

    /**
     * Reorders carrier positions.
     * Called after deleting a carrier.
     *
     * @since 1.5.0
     * @return bool $return
     */
    public static function cleanPositions()
    {
        $return = true;

        $sql = '
		SELECT `id_carrier`
		FROM `'._DB_PREFIX_.'carrier`
		WHERE `deleted` = 0
		ORDER BY `position` ASC';
        $result = Db::getInstance()->executeS($sql);

        $i = 0;
        foreach ($result as $value)
            $return = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'carrier`
			SET `position` = '.(int)$i++.'
			WHERE `id_carrier` = '.(int)$value['id_carrier']);
        return $return;
    }

    /**
     * Gets the highest carrier position
     *
     * @since 1.5.0
     * @return int $position
     */
    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`)
				FROM `'._DB_PREFIX_.'carrier`
				WHERE `deleted` = 0';
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }

    public  function getModuleAssign( $module_name = '', $name_hook = '' ){
		//$module_id = 7 ; $id_hook = 21 ;
		if(!$module_name || !$name_hook)  return ;
			$module = Module::getInstanceByName($module_name);	
			$module_id = $module->id;
			$id_hook = Hook::getIdByName($name_hook);
			$hook_name = $name_hook;
			if(!$module) return ;
			$module_name = $module->name;
		if( Validate::isLoadedObject($module) && $module->id ){
			$array = array();
			$array['id_hook']   = $id_hook;
			$array['module'] 	= $module_name;
			$array['id_module'] = $module->id;
			if(_PS_VERSION_ < "1.5"){ 
				return self::lofHookExec( $hook_name, array(), $module->id, $array );
			}else{ 
				//$hook_name = substr($hook_name, 7, strlen($hook_name));
				return self::renderModuleByHookV15( $hook_name, array(), $module->id, $array );
			}
		}
		return '';			
	}
	
	public static function renderModuleByHook( $hook_name, $hookArgs = array(), $id_module = NULL, $array = array() ){
		global $cart, $cookie;
                if(!$hook_name || !$id_module) return ;
		if ((!empty($id_module) AND !Validate::isUnsignedId($id_module)) OR !Validate::isHookName($hook_name))
			die(Tools::displayError());

		$live_edit = false;
		if (!isset($hookArgs['cookie']) OR !$hookArgs['cookie'])
			$hookArgs['cookie'] = $cookie;
		if (!isset($hookArgs['cart']) OR !$hookArgs['cart'])
			$hookArgs['cart'] = $cart;
		$hook_name = strtolower($hook_name);
		$altern = 0;
		
		if ($id_module AND $id_module != $array['id_module'])
			return;
		if (!($moduleInstance = Module::getInstanceByName($array['module'])))
			return;
		if (is_callable(array($moduleInstance, 'hook'.$hook_name)))
		{
			$hookArgs['altern'] = ++$altern;
			$output = call_user_func(array($moduleInstance, 'hook'.$hook_name), $hookArgs);
		}
		return $output;
	}
	
	public static function renderModuleByHookV15( $hook_name, $hookArgs = array(), $id_module = NULL, $array = array() ){
		global $cart, $cookie;
               
                if(!$hook_name || !$id_module) return ;
		if ((!empty($id_module) AND !Validate::isUnsignedId($id_module)) OR !Validate::isHookName($hook_name))
			die(Tools::displayError());
		
		if (!isset($hookArgs['cookie']) OR !$hookArgs['cookie'])
			$hookArgs['cookie'] = $cookie;
		if (!isset($hookArgs['cart']) OR !$hookArgs['cart'])
			$hookArgs['cart'] = $cart;
		
		if ($id_module AND $id_module != $array['id_module'])
			return ;
		if (!($moduleInstance = Module::getInstanceByName($array['module'])))
			return ;
		$retro_hook_name = Hook::getRetroHookName($hook_name);
		$hook_callable = is_callable(array($moduleInstance, 'hook'.$hook_name));
		$hook_retro_callable = is_callable(array($moduleInstance, 'hook'.$retro_hook_name));
		
		$output = '';
		if (($hook_callable || $hook_retro_callable) && Module::preCall($moduleInstance->name))
		{ 
			if ($hook_callable)
				$output = $moduleInstance->{'hook'.$hook_name}($hookArgs);
			else if ($hook_retro_callable)
				$output = $moduleInstance->{'hook'.$retro_hook_name}($hookArgs);
		}

		return $output;
	}

}