<?php

declare(strict_types=1);

namespace App\Enum\User;

final class Role
{
    public const string ROLE_ADMIN = 'ROLE_ADMIN';

    public const string ROLE_USER = 'ROLE_USER';

    public const string ROLE_APP_CAN_VIEW_BACKLOG = 'ROLE_APP_CAN_VIEW_BACKLOG';

    public const string ROLE_APP_CAN_ASSIGNEE = 'ROLE_APP_CAN_ASSIGNEE';

    public const string ROLE_APP_KANBAN = 'ROLE_APP_KANBAN';

    public const string ROLE_APP_VIEW_KANBAN = 'ROLE_APP_VIEW_KANBAN';

    /**
     * @return array<string,string>
     */
    public static function getList(): array
    {
        return [
            'user.roles.role_admin' => self::ROLE_ADMIN,
            'user.roles.role_user' => self::ROLE_USER,
            'user.roles.role_app_can_assignee' => self::ROLE_APP_CAN_ASSIGNEE,
            'user.roles.role_app_can_view_backlog' => self::ROLE_APP_CAN_VIEW_BACKLOG,
            'user.roles.role_app_kanban' => self::ROLE_APP_KANBAN,
            'user.roles.role_app_view_kanban' => self::ROLE_APP_VIEW_KANBAN,
        ];
    }
}
