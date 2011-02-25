# Message

A flash messaging system for Kohana v3.1 and higher.

To use, download the source, extract and rename to message. Move that folder into your modules directory and activate in your bootstrap.

## Usage
To set a flash message all it takes is the following
~~~
Message::set($type, $message);
~~~

## Wrapper methods
There are also methods that are wrappers for the different types of messages
~~~
Message::error($message);
Message::success($message);
Message::notice($message);
Message::warn($message);
~~~

| Variable | Info |
|----------|------|
|`$type` | This gets set as the `class` of the message. You can use anything, but the [constants](#constants) are preferred. |
|`$message` | A message string or array of message strings |

When you need to get a message you can use either of the following methods:
~~~
echo Message::display();
echo Message::render();
~~~

## Constants

There are 4 constants you can use to set a message.

| Constant | Value |
| -------- | ----- |
| Message::ERROR | `"error"` |
| Message::NOTICE | `"notice"` |
| Message::SUCCESS | `"success"` |
| Message::WARN | `"warn"` |

## Style
The message class produces the following code by default
~~~
<ul id="message_id" class"type">
	<li>Message</li>
	... Repeated if an array
</ul>
~~~

To style, set #message and the classes for the constants
.error, .success, .notice, .warn

-----

## Sample

Here is a quick example of using the message class when validating a form.

~~~
$validation = new Validate($_POST);
$validation->rule(.....) <-- Add rules

if( $validation->check() )
{
	// Validation passed
	Message::success('Form Success!');
	// OR -> Message::set(Message::SUCCESS, 'Form Success!');
}
else
{
	// Validation failed
	Message::error($validation->errors('_form_');
	// OR -> Message::set(Message::ERROR, $validation->errors('_form_'));
}
~~~
