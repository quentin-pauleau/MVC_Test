<?php
namespace Modules\UUID;

enum UUIDVersions: int {
	case V1 = 1;
	case V3 = 3;
	case V4 = 4;
	case V5 = 5;
	case V7 = 7;
}