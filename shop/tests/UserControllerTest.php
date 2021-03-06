<?php


class UserControllerTest extends \PHPUnit\Framework\TestCase
{
    private $controller, $model, $loader;
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->model = $this->createMock(\model\UserModel::class);
        $this->model->method('checkAuth')->will($this->returnArgument(0));
        $this->model->method('getUser')->willReturn([]);

        $this->loader = $this->getMockBuilder(\services\Autoloader::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $this->loader->method('render')->willReturn('string');


        $this->controller = new \controllers\UserController('index', $this->model, '');
    }

    public function test_action_index()
    {
        $this->assertContains('string', $this->loader->render());
    }
}