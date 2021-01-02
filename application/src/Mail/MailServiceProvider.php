<?php 
namespace Application\Src\Mail;
use \Concrete\Core\Foundation\Service\Provider as ServiceProvider;

class MailServiceProvider extends ServiceProvider {

	public function register() {
		$register = array(
			'helper/mail' => '\Application\Src\Mail\Service',
			'mail' => '\Application\Src\Mail\Service'
		);

		foreach($register as $key => $value) {
			$this->app->bind($key, $value);
		}
	}


}