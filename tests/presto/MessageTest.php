<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

/**
 * Tests Presto's Message Class
 *
 * @group presto
 * @group presto.message
 *
 * @package		Presto
 * @author		Dave Widmer
 * @copyright	2011 © Dave Widmer
 */
class Presto_MessageTest extends Kohana_Unittest_TestCase
{
	/**
	 * Make sure the message is cleared at the end of each test.
	 */
	public function tearDown()
	{
		Message::clear();
		parent::tearDown();
	}

	/**
	 * Making sure message is empty on initialization.
	 */
	public function test_empty_on_init()
	{
		$this->assertNull(Message::get());
	}

	/**
	 * Test to make sure all of the constants work correctly.
	 */
	public function test_message_types()
	{
		// Error
		Message::error("This is an error");
		$msg = Message::get();
		$this->assertSame($msg->type, Message::ERROR);

		// Notice
		Message::notice("This is a notice");
		$msg = Message::get();
		$this->assertSame($msg->type, Message::NOTICE);

		// Success
		Message::success("This is a success");
		$msg = Message::get();
		$this->assertSame($msg->type, Message::SUCCESS);

		// Warn
		Message::warn("This is a warning");
		$msg = Message::get();
		$this->assertSame($msg->type, Message::WARN);

		// Custom
		Message::set("custom", "This is a custom type");
		$msg = Message::get();
		$this->assertSame($msg->type, "custom");
	}

	/**
	 * Test that the messages are an array.
	 */
	public function test_message_as_array()
	{
		Message::notice("This is only a test!");
		$msg = Message::get();
		$this->assertTrue(is_array($msg->message));
		$this->assertEquals(1, count($msg->message));

		Message::notice(array("This is only a test!", "with a second message"));
		$msg = Message::get();
		$this->assertTrue(is_array($msg->message));
		$this->assertEquals(2, count($msg->message));
	}

	/**
	 * Tests that the render and display functions have the same output.
	 */
	public function test_display_clears_message()
	{
		Message::success("This message will be cleared");
		$this->assertThat(Message::display(), $this->logicalNot($this->equalTo("")));
		$this->assertThat(Message::display(), $this->equalTo(""));
	}

}
