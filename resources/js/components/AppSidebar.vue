<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Bell, LayoutGrid, Shield, User } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

interface User {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
}

interface PageProps {
  auth: {
    user: User;
  };
  [key: string]: any;
}

const page = usePage<PageProps>();
const user = computed(() => page.props.auth.user);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
];

const footerNavItems = computed(() => {
    const adminLinks = [
        {
            title: 'Admin - Store Hours',
            href: '/admin/store-hours',
            icon: Shield,
        },
    ];

    const mainLinks = [
        {
            title: 'Notification Preferences',
            href: '/user/notification-preferences',
            icon: Bell,
        }
    ]

    if (user.value.is_admin) {
        return [...mainLinks, ...adminLinks];
    } else {
        return mainLinks;
    }
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
