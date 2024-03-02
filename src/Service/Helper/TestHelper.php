<?php

declare(strict_types=1);

namespace App\Service\Helper;

abstract class TestHelper
{
    public const CREATOR_EMAIL = 'creator@mail.com';
    public const CREATOR_NAME = 'creator';
    public const CREATOR_PLAIN_PASSWORD = 'passPASS123@';
    public const USER_EMAIL = 'user@test.com';
    public const USER_NAME = 'user';
    public const SUPER_USER_EMAIL = 'superuser@test.com';
    public const SUPER_USER_NAME = 'super';
    public const DEACTIVATED_USER_EMAIL = 'deactivated@test.com';
    public const DEACTIVATED_USER_NAME = 'deactivated';
    public const DEACTIVATED_USER_PLAIN_PASSWORD = 'passPASS123@';
    public const USER_PLAIN_PASSWORD = 'passPASS123@';
    public const SUPER_USER_PLAIN_PASSWORD = '@321ASSPassp';
    public const GAME_CODE_IMAGE_TAG = 'game_tag_image';
}
