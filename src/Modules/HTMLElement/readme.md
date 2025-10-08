# HTMLElement Mudule

This module is used to create HTML elements using PHP.

It uses [DaisyUI](https://daisyui.com/) components for faster and easy styling.


## How To Use
If you dont have this module setup, follow the instructions in the `Add The Module` section.

### Create Elements
#### Simple HTML element :
```php
$element = new Element('div', 'Hello World');
echo $element;
// <div>Hello World</div>
```

#### DaisyUI Element : 
```php
$btn = new Button('Click Me!');
echo $btn;
// <button class="btn">Click Me!</button>
```

```php
$title = new Title('This is a Heading Title');
echo $title;
// <h1>This is a Heading Title</h1>
```

#### Stack Elements
```php
$card = new Card;
$card->add(new Title('John Doe'));
$card->add(new Paragraph('Lorem ipsum dolor sit amet consectetur adipisicing elit.'));

echo $heading;
/*
<div class="card">
	<h1>This is a Heading Title</h1>
	<p>This is a paragraph</p>
</div>
*/
```

#### Build Elements
```php
$card = (new Card)
	->setTitle('John Doe');
	->setParagraph('Lorem ipsum dolor sit amet consectetur adipisicing elit.')
;

echo $card;

/*
<div class="card">
	<h1>This is a Heading Title</h1>
	<p>This is a paragraph</p>
</div>
*/
```

### Diplay Elements
To display an element it is possible to echo the element or call the `display()` function of the element.
```php
echo $element; // <div>Hello World</div>
$element->display(); // <div>Hello World</div>
```

Most of the time elements are displayed from a `View` file so the shortend echo syntax (`<?= $element ?>` ) should be used instead.
```html
<div>
	<?= $element ?>
</div>
```

### Making Personalized Component

#### Using Functions
```php
function userCard($user) {
	return (new Card)
		->setTitle($user['name']),
		->setParagraph($user['bio'])
	;
}

$user = [
	'name' => 'John Doe',
	'bio' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
];

echo userCard($user);

/*
<div class="card">
	<h1>John Doe</h1>
	<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
</div>
*/
```


#### Using Classes
It is good practice to implement the display logic into `Component` classes that will handle all the display logic for a specific type of object.

Use static functions to avoid having to instanciate the `Component` classes to call a display function.
Use `...$arg` when the component to display can allow multiple objects to be displayed (ex: lists, tables, etc...).
Use `#region` and `#endregion` or make multiples `Component` classes to group related display functions together.


```php
// User Object class
class User
{
	public string $name;
	public string $bio;

	public function __construct(string $name, string $bio)
	{
		$this->name = $name;
		$this->bio = $bio;
	}
}

// It is recommended to separate the object from its diplay components logic
// This way components can be used for multiples objects and the object itself is not filled with display logic
class UserComponents
{
	static function card($user) {
		return (new Card)
			->setTitle($user['name']),
			->setParagraph($user['bio'])
		;
	}
}

// Instanciate an exemple user
$user = new User(
	'John Doe', 
	'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
);

// Diplay the card using the static method 
echo UserComponents::card($user);

/*
<div class="card">
	<h1>John Doe</h1>
	<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
</div>
*/
```


## Documentation


## Styling
All elements created with this module use the daisyUI CSS framework.
To learn more about how to style your elements visit their website at https://daisyui.com/.


## Installation

### Dependancies
Module dependant by the HTMLElement Module :
- **NONE**


### Add The Module


## Desinstallation
Follow the following steps to desinstall the HTMLElement Module

### Dependant Modules
Module dependant on the HTMLElement Module :
- [FormHandling](../FormHandling/)

These modules require the HTMLElement Module to work properly, it is recommended to remove them with among side the HTMLElement Module.

You can click of each module name to see if they are installed, if they are open the readme file to follow the desinstallation steps.


### Remove The Module