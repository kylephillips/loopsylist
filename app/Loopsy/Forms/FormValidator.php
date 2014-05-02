<?php
namespace Loopsy\Forms;

use Illuminate\Validation\Factory as Validator;

abstract class FormValidator {

	protected $validator;

	protected $validation;

	function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	public function validate(array $formData)
	{
		$this->validation = $this->validator->make($formData, $this->getValidationData());

		if ( $this->validation->fails() )
		{
			throw new FormValidationException('Validation Failed', $this->getValidationErrors());
		}

		return true;
	}

	protected function getValidationData()
	{
		return $this->rules;
	}

	protected function getValidationErrors()
	{
		return $this->validation->errors();
	}

}