<?php
/**
 * TaggableCacheBehavior
 * 
 *
 * Based on article http://korzh.net/2011-04-tegirovanie-kesha-v-yii-framework-eto-ne-bolno.html
 *
 *
 * How to use:
 * 1. Configure cache component in config to use TaggableCacheBehavior:
 * 
 * 2. Caching with tag1 and tag2
 * 		Yii::app()->cache->set($key, $value, 0, new TagDependency('tag1', 'tag2'));
 *
 * 3. Clear cache by tag2 when it is needed
 * 		Yii::app()->cache->clear('tag2');
 *
 *
 *
 */
class TagDependency implements ICacheDependency {

	// protected $timestamp;
	// protected $tags=array();

	// /**
	//  * List of tags is used as arguments for dependency
	//  *
	//  * @params tag1, tag2, ..., tagN
	//  */
	// public function __construct() {
	// 	$this->tags = func_get_args();
	// }

	// /**
	//  * Evaluates the dependency by generating and saving the data related with dependency.
	//  * This method is invoked by cache before writing data into it.
	//  */
	// public function evaluateDependency() {
	// 	$this->timestamp = microtime(true);

	// }

	// *
	//  * @return boolean whether the dependency has changed.
	 
	// public function getHasChanged() {

		
	// 	$tags = array_map(function($i) { return TaggableCacheBehavior::PREFIX.$i; }, $this->tags);

	// 	if (count($tags)==0) {
	// 		return true;
	// 	}

	// 	$values = Yii::app()->cache->mget($tags);

	// 	if ($values === false) return true;
	// 	foreach ($values as $value) {
			
	// 		// if ($value === false) {
	// 		// 	return true;
	// 		// }
	// 		if ((float)$value > $this->timestamp) {
			
	// 			return true;
	// 		}
	// 	}
		
		
	// 	return false;
	// }

	// Список тегов, поступивших в конструкторе
    public $_tags = null;

    // Ссылка на объект реализующий интерфейс \ICache
    public $_backend;

    // Ассоциативный массив версий тегов
    public $_tag_versions = null;

    /**
     * Принимает на вход кучу тегов, которыми помечается кеш
     */
    public function __construct() {
        $this->_tags = func_get_args();
    }

    public function initBackend()
    {
        $this->_backend = Yii::app()->cache;
    }

    /**
     * Этот метод вызывается до сохранения данных в кеш.
     * В нём мы устанавливаем версии тегов указанных в конструкторе и затем сохраненных в property:_tags
     */
    public function evaluateDependency() {
        $this->initBackend();
        $this->_tag_versions = null;

        if($this->_tags === null || !is_array($this->_tags)) {
            return;
        }

        if (!$this->_backend) return;

        $tagsWithVersion = array();

        foreach ($this->_tags as $tag) {
            $mangledTag = Yii::app()->cache->mangleTag($tag);
            $tagVersion = $this->_backend->get($mangledTag);
            if ($tagVersion === false) {
                $tagVersion = Yii::app()->cache->generateNewTagVersion();
                $this->_backend->set($mangledTag, $tagVersion, 0);
            }
            $tagsWithVersion[$tag] = $tagVersion;
        }

        $this->_tag_versions = $tagsWithVersion;

        return;
    }

    /**
     * Возвращает true, если данные кеша устарели
     */
    public function getHasChanged()
    {
        $this->initBackend();

        if ($this->_tag_versions === null || !is_array($this->_tag_versions)) {
            return true;
        }
        
        // Выдергиваем текущие версии тегов сохраненных с записью в кеше
        $allMangledTagValues = $this->_backend->mget(Yii::app()->cache->mangleTags(array_keys($this->_tag_versions)));

        // Перебираем теги сохраненные в dependency. Т.е. здесь
        foreach ($this->_tag_versions as $tag => $savedTagVersion) {

            $mangleTag = Yii::app()->cache->mangleTag($tag);

            // Тег мог "протухнуть", тогда считаем кеш измененным
            if (!isset($allMangledTagValues[$mangleTag])) {
                return true;
            }

            $actualTagVersion = $allMangledTagValues[$mangleTag];

            // Если сменилась версия тега, то кеш изменили
            if ($actualTagVersion !== $savedTagVersion) {
                return true;
            }
        }

        return false;
    }
}