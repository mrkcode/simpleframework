<?php
/**
 * Класс для авторизации.
 *
 * @author     Марк Альтерман
 * @copyright  Copyright (c) 2014
 */
 
 /**
  * @author   Марк Альтерман
  * @version  1.0
  */
class Auth {
	
	/**
	 * Обработчик(функция или метод) возвращающий привелегии и данные
	 * @var mixed
	 */
	private $handler;
	
	/**
	 * Название авторизации
	 * @var string
	 */
	private $name;
	
	/**
	 * Конструктор
	 * 
	 * Устанавливает обработчик данных, стартует сессию
	 * 
	 * @param mixed $handler
	 * @return void
	 */
	public function __construct($handler, $name = null)
	{
		if (!is_callable($handler)) {
			throw new Exception('Handler not callable');
		}
		
		if (!$name) {
			$name = dirname($_SERVER['SCRIPT_NAME']);
		}
		
		$this->handler = $handler;
		$this->name    = $name; 
		
		if (!session_id()) {
			session_start();
		}
	}
	
	/**
	 * Авторизует пользователя и регистрирует привелегии с помощью назначенного обработчика
	 * 
	 * @param string $user_name
	 * @param string $password
	 * @return bool
	 */
	public function login($user_name, $password)
	{
		$this->logout();
		
		$result = call_user_func_array($this->handler, array($user_name, $password));
		
		if (!$result) {
			return false;
		}
		
		if (!is_array($result) || count($result) !== 2) {
			throw new Exception('Incorrect result handler');
		}
		
		$_SESSION['AUTH'][$this->name] = array(
			'permissions'  => (array) array_shift($result),
			'data'         => (array) array_shift($result)
		);
		
		return true;
	}
	
	/**
	 * Проверяет авторизован пользователь или нет
	 * 
	 * @return bool
	 */
	public function logged()
	{
		return isset($_SESSION['AUTH'][$this->name]);
	}
	
	/**
	 * Удаляет данные авторизации
	 * 
	 * @return void
	 */
	public function logout()
	{
		unset($_SESSION['AUTH'][$this->name]);
	}
	
	/**
	 * Возвращает данные по ключу из массива возвращенным обработчиком
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if (isset($_SESSION['AUTH'][$this->name]['data'][$key])) {
			return $_SESSION['AUTH'][$this->name]['data'][$key];
		}
	}
	
	/**
	 * Проверяет наличие привелегии
	 * 
	 * @return bool
	 */
	public function can($permission)
	{
		return isset($_SESSION['AUTH'][$this->name]['permission'])
            && in_array($permission, $_SESSION['AUTH'][$this->name]['permission']);
	}
}