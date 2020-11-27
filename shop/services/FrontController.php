<?php


namespace services;


use config\Config;
use Twig\Node\Node;

class FrontController
{
    /**
     * @var $action - метод контроллера из адресной строки после фильтрации в роутере
     */
    private $action;

    /**
     * @var $props - если есть - дополнительные параметры из адресной строки (номер страницы, id товара или пользователя....)
     * @var $model - модель в зависимости от контроллера
     */
    protected $props, $model;

    /**
     * FrontController constructor.
     * @param $action - метод контроллера из адресной строки после фильтрации в роутере
     * @param $model - модель в зависимости от контроллера
     * @param $props - если есть - дополнительные параметры из адресной строки (номер страницы, id товара или пользователя....)
     */
    public function __construct($action, $model, $props)
    {
        $this->action = $action;
        $this->props = $props;
        $this->model = new $model();
    }

    /**
     * загрузка страницы
     */
    public function getAction()
    {
        $access = $this->beforeAction();
        if ($access === true) {
            $method = 'action_' . $this->action;
            echo $this->getLayout($this->$method((int)$this->props));
        } else {
            echo $this->getLayout($this->action_error($access));
        }

        $this->afterAction();
    }

    /**
     * проверяем есть ли такой метод и разрешен ли доступ перед началом загрузки страницы
     * @return bool
     */
    protected function beforeAction()
    {
        $hasMethod = method_exists($this, 'action_' . $this->action);

        $access = Config::access();
        if ($access > 1 && $hasMethod) {
            $methods = get_class_methods('\services\AuthHelper');
            return $this->model->access->{$methods[$access - 1]}();
        } else {
            return $hasMethod;
        }
    }

    /**
     * заглушка, но на перспективу, выполнить какие-то действия после загрузки страницы
     * @return bool
     */
    protected function afterAction()
    {
        // write history & log
        return true;
    }

    /**
     * загружаем layout и страницу с контентом
     * @param $method - наименование экшена, который грузит контент
     * @return string - разметка страницы готовая для отображения
     */
    protected function getLayout($method)
    {
        $page = new Autoloader('layouts/index');
        return $page->render([
            'title' => $this->action,
            'content' => $method,
            'menu' => Config::menu(),
            'submenu' => Config::subMenu()
        ]);
    }

    /**
     * страница ошибки, если что-то пошло не так (запрещен доступ, страница не найдена и прочее
     * @param string $props - строка ошибки (по умолчанию '' - передаем пустую строку, чтобы вывести 404 в \Autoloader->render()
     * @return string - сгенерированная страница ошибки
     */
    protected function action_error($props = '')
    {
        $page = new Autoloader('error');
        return $page->render($props ? ['content' => $props] : null);
    }
}