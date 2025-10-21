<?php
namespace Modules\FormHandling;


enum FormFieldTypes
{
	//* Inputs
	case INPUT_TEXT;
	case INPUT_PASSWORD;
	case INPUT_EMAIL;
	case INPUT_NUMBER;
	case INPUT_URL;
	case INPUT_SEARCH;

	//* Areas
	case TEXTAREA;

	//* Pickers
	case COLOR_PICKER;

	case DATE_PICKER;
	case TIME_PICKER;
	case DATETIME_PICKER;
	case MONTH_PICKER;
	case WEEK_PICKER;
	case YEAR_PICKER;


	//* Uploaders

	case FILE_UPLOAD;
	case IMAGE_UPLOAD;
	case VIDEO_UPLOAD;
	case AUDIO_UPLOAD;
	case DOCUMENT_UPLOAD;
	case ARCHIVE_UPLOAD;

	//* Selects
	case CHECKBOX;
	case RADIO;
	case SWITCH;
	case SELECT;
	case MULTI_SELECT;

	case SLIDER;
}