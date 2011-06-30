<?php
/**
 *  Copyright 2010 Nickolas Whiting
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 *
 * @author  Nickolas Whiting  <me@nwhiting.com>
 * @package  prggmr
 * @copyright  Copyright (c), 2010 Nickolas Whiting
 */

/**
 * \prggmr\Event Unit Tests
 */

include_once 'bootstrap.php';

class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
	public function testFire()
	{
		$sub = new \prggmr\Subscription(function(){
			return 'helloworld';
		});
		$this->assertEquals('helloworld', $sub->fire());
	}

	public function testIdentifier()
	{
		$sub = new \prggmr\Subscription(function(){;}, 'test');
		$this->assertEquals('test', $sub->getIdentifier());
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testException()
	{
		$sub = new \prggmr\Subscription(function(){
			throw new \Exception(
				'I am an exception'
			);
		});
		$sub->fire();
	}

	public function testEventFireParameters()
	{
		$sub = new \prggmr\Subscription(function($param1){
			return $param1;
		});
		$this->assertEquals('helloworld', $sub->fire('helloworld'));

		$sub = new \prggmr\Subscription(function($param1){
			return $param1;
		});
		$this->assertEquals('helloworld', $sub->fire(array('helloworld')));

		$sub = new \prggmr\Subscription(function($param1, $param2){
			return $param1.$param2;
		});
		$this->assertEquals('helloworld', $sub->fire('hello', 'world'));
	}

	public function testExhausting()
	{
		$sub = new \prggmr\Subscription(function(){;}, null, 1);
		$this->assertEquals(1, $sub->limit());
		$this->assertEquals(0, $sub->count());
		$this->assertFalse($sub->isExhausted());
		$sub->fire();
		 $this->assertEquals(1, $sub->count());
		$this->assertTrue($sub->isExhausted());
		$sub = new \prggmr\Subscription(function(){;}, null, 10);
		for($i=0;$i!=9;$i++) {
			$sub->fire();
			$this->assertFalse($sub->isExhausted());
		}
		$sub->fire();
		$this->assertTrue($sub->isExhausted());
		$sub = new \prggmr\Subscription(function(){;}, null, 0);
		$this->assertFalse($sub->isExhausted());
		while(true) {
			$sub->fire();
			if ($sub->count() >= 25) {
				break;
			}
		}
		$this->assertFalse($sub->isExhausted());
	}
}