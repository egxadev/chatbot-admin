import { NavItem } from '@/types';
import { BrainCircuit, ChartArea, MessagesSquare, QrCode, UserRoundCog } from 'lucide-react';

export const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: ChartArea,
        permission: ['dashboard.index'],
    },
    {
        title: 'Scan QR Code',
        href: '/scan',
        icon: QrCode,
        permission: ['scan.index'],
    },
    {
        title: 'Chat History',
        href: '/chat-histories',
        icon: MessagesSquare,
        permission: ['chat_histories.index'],
    },
    {
        title: 'Knowledge Base',
        href: '/knowledge-bases',
        icon: BrainCircuit,
        permission: ['knowledge_bases.index'],
    },
    {
        title: 'User Management',
        href: '#',
        icon: UserRoundCog,
        permission: ['permissions.index', 'roles.index', 'users.index'],
        items: [
            {
                title: 'Permission',
                href: '/permissions',
            },
            {
                title: 'Role',
                href: '/roles',
            },
            {
                title: 'User',
                href: '/users',
            },
        ],
    },
];

export const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/react-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits',
    //     icon: BookOpen,
    // },
];

export const settingsNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
        icon: null,
    },
    {
        title: 'Password',
        href: '/settings/password',
        icon: null,
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
        icon: null,
    },
];
