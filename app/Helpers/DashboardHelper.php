<?php

/**
 * DashboardHelper - Helpers for dashboard/admin navigation
 */

if (!function_exists('getDashboardUrl')) {
    /**
     * Get appropriate dashboard URL based on user role
     *
     * @param array|null $userSession Session user data (if null, uses current session)
     * @return string Dashboard URL (/admin for admins, /dashboard for normal users)
     */
    function getDashboardUrl(?array $userSession = null): string
    {
        if ($userSession === null) {
            $userSession = session()->get() ?? [];
        }

        $isAdmin = (int) ($userSession['is_admin'] ?? 0) === 1;
        return $isAdmin ? '/admin' : '/dashboard';
    }
}

if (!function_exists('isUserAdmin')) {
    /**
     * Check if current user is admin
     *
     * @param array|null $userSession Session user data (if null, uses current session)
     * @return bool True if user is admin, false otherwise
     */
    function isUserAdmin(?array $userSession = null): bool
    {
        if ($userSession === null) {
            $userSession = session()->get() ?? [];
        }

        return (int) ($userSession['is_admin'] ?? 0) === 1;
    }
}

if (!function_exists('isUserLoggedIn')) {
    /**
     * Check if user is logged in
     *
     * @return bool True if user is logged in, false otherwise
     */
    function isUserLoggedIn(): bool
    {
        return (bool) session()->get('is_logged_in');
    }
}

if (!function_exists('getUserName')) {
    /**
     * Get current logged-in user name
     *
     * @return string|null User name or null if not logged in
     */
    function getUserName(): ?string
    {
        return session()->get('user_name');
    }
}

if (!function_exists('getUserEmail')) {
    /**
     * Get current logged-in user email
     *
     * @return string|null User email or null if not logged in
     */
    function getUserEmail(): ?string
    {
        return session()->get('user_email');
    }
}
