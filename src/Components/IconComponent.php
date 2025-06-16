<?php
namespace Components;

class IconComponent 
{
	public const DEFAULT_CLASS = "";
	public ?string $id = "";
	public string $class = "";
	public string $link = "";
	public string $alt = "";

	public function __construct(?string $id, string $class, string $link, string $alt = "") {
		$this->icon = $id;
		$this->class = $class;
		$this->link = $link;
		$this->alt = $alt;
	}

	public function SetClass(string $class): self {
		$this->class = $class;
		return $this;
	}

	public function Show(): void {
		?>
			<img <?php if ($this->id != null): ?> id="<?= $this->id ?>" <?php endif; ?> 
					class="<?= $this->class ?>" src="<?= $this->link ?>" alt="<?= $this->alt ?>"
			>
		<?php
	}

	public static function Display(?string $id, string $class, string $link = "", string $alt = ""): void {
		?>
			<img <?php if ($id != null): ?> id="<?= $id ?>" <?php endif; ?> 
					class="<?= $class ?>" src="<?= $link ?>" alt="<?= $alt ?>"
			>
		<?php
	}

	public static function GetCheckCircle(): IconComponent {
		return new IconComponent(null, "content-center", "public/Icons/check_circle.svg", "check");
	}

	public static function GetAddCircle(): IconComponent {
		return new IconComponent(null, "", "public/Icons/check_circle.svg", "");
	}

	public static function GetAdd(): IconComponent {
		return new IconComponent(null, "", "public/Icons/add.svg", "");
	}

	public static function GetDelete(): IconComponent {
		return new IconComponent(null, "", "public/Icons/delete.svg", "");
	}

	public static function GetEdit(): IconComponent {
		return new IconComponent(null, "", "public/Icons/edit.svg", "");
	}

}