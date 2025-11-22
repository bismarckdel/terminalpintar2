<script setup>
import { ref, computed } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import FlashMessage from "@/components/FlashMessage.vue";
import {
  Bars3Icon,
  XMarkIcon,
  BellIcon,
  UserCircleIcon,
  ArrowRightOnRectangleIcon,
  ExclamationCircleIcon
} from "@heroicons/vue/24/outline";

const page = usePage();

const showingNavigationDropdown = ref(false);
const showLogoutModal = ref(false);
const isProfileOpen = ref(false);

const userName = computed(() => page.props.auth?.user?.nama || "Pengguna");
const userInitial = computed(() => userName.value.charAt(0).toUpperCase());
const userRoles = computed(() => {
  const roles = page.props.auth?.roles ?? [];

  if (Array.isArray(roles)) {
    return roles.map((role) => {
      if (typeof role === "string") {
        return role;
      }
      if (role && typeof role === "object") {
        return role.name || role.title || "";
      }
      return "";
    });
  }

  if (typeof roles === "string") {
    return [roles];
  }

  if (roles && typeof roles === "object") {
    const values = Object.values(roles).flat();
    return values.map((value) => {
      if (typeof value === "string") {
        return value;
      }
      if (value && typeof value === "object") {
        return value.name || value.title || "";
      }
      return "";
    });
  }

  return [];
});

const hasRole = (role) =>
  userRoles.value.some((assignedRole) =>
    String(assignedRole || "").toLowerCase() === role.toLowerCase()
  );

const closeMenus = () => {
  showingNavigationDropdown.value = false;
  isProfileOpen.value = false;
};

const toggleProfile = () => {
  isProfileOpen.value = !isProfileOpen.value;
};

const handleLogout = () => {
  router.post("/logout", {}, {
    onFinish: () => {
      showLogoutModal.value = false;
      closeMenus();
    }
  });
};

const adminLinks = [
  {
    label: "Dashboard",
    href: "/dashboardadmin",
    match: ["/dashboardadmin"]
  },
  {
    label: "Kelola Pengguna",
    href: "/admin/user",
    match: ["/admin/user"]
  },
  {
    label: "Kelola Siswa",
    href: "/admin/siswa",
    match: ["/admin/siswa"]
  },
  {
    label: "Berita & Dokumentasi",
    href: "/admin/berita",
    match: ["/admin/berita"]
  },
  {
    label: "Kelola Perpustakaan",
    href: "/admin/perpustakaan",
    match: ["/admin/perpustakaan"]
  }
];

const isLinkActive = (link) =>
  link.match.some((segment) => page.url.startsWith(segment));

const navClasses = (isActive) =>
  isActive
    ? "inline-flex items-center px-1 pt-1 border-b-4 border-[#78AE4E] text-sm font-semibold text-[#78AE4E] focus:outline-none transition duration-150 ease-in-out h-full"
    : "inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-semibold text-gray-900 hover:text-[#78AE4E] hover:border-[#78AE4E] focus:outline-none transition duration-150 ease-in-out h-full";

</script>

<template>
  <div class="min-h-screen bg-gray-100 font-sans">
    <FlashMessage />

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="shrink-0 flex items-center">
              <Link href="/">
                <img class="block h-9 w-auto" src="C:\laragon\www\TerminalPintar\public\images\Logo.png" alt="Terminal Pintar" />
              </Link>
              <span class="ml-2 text-lg font-bold text-[#78AE4E] hidden md:block">Terminal Pintar</span>
            </div>

            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              <template v-if="hasRole('Admin')">
                <Link
                  v-for="link in adminLinks"
                  :key="link.href"
                  :href="link.href"
                  :class="navClasses(isLinkActive(link))"
                >
                  {{ link.label }}
                </Link>
              </template>
            </div>
          </div>

          <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
            <button
              class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring"
              aria-label="Notifikasi"
            >
              <BellIcon class="h-6 w-6" />
            </button>

            <div class="relative ml-3">
              <div class="flex items-center gap-3 cursor-pointer" @click="toggleProfile">
                <div class="text-right hidden md:block">
                  <div class="text-sm font-medium text-gray-800">{{ userName }}</div>
                  <div class="text-xs text-gray-500">{{ userRoles[0] || 'User' }}</div>
                </div>
                <div
                  class="h-9 w-9 rounded-full bg-green-100 flex items-center justify-center text-[#78AE4E] font-bold border border-green-200"
                >
                  {{ userInitial }}
                </div>
              </div>

              <div
                v-if="isProfileOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
              >
                <div class="px-4 py-3">
                  <p class="text-sm font-medium text-gray-800">{{ userName }}</p>
                  <p class="text-xs text-gray-500">{{ userRoles[0] || 'User' }}</p>
                </div>
                <button
                  type="button"
                  class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                  @click="showLogoutModal = true"
                >
                  <ArrowRightOnRectangleIcon class="w-4 h-4" />
                  Keluar
                </button>
              </div>

              <div
                v-if="isProfileOpen"
                class="fixed inset-0 z-40"
                @click="isProfileOpen = false"
              ></div>
            </div>
          </div>

          <div class="-mr-2 flex items-center sm:hidden">
            <button
              type="button"
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring"
              aria-label="Toggle navigation"
            >
              <Bars3Icon v-if="!showingNavigationDropdown" class="h-6 w-6" />
              <XMarkIcon v-else class="h-6 w-6" />
            </button>
          </div>
        </div>
      </div>

      <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <template v-if="hasRole('Admin')">
            <Link
              v-for="link in adminLinks"
              :key="`mobile-${link.href}`"
              :href="link.href"
              class="block w-full pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out"
              :class="
                isLinkActive(link)
                  ? 'border-[#78AE4E] text-[#78AE4E] bg-green-50'
                  : 'border-transparent text-gray-600 hover:text-[#78AE4E] hover:bg-green-50 hover:border-[#78AE4E]'
              "
              @click="showingNavigationDropdown = false"
            >
              {{ link.label }}
            </Link>
          </template>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
          <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ userName }}</div>
            <div class="font-medium text-sm text-gray-500">{{ userRoles[0] || 'User' }}</div>
          </div>
          <div class="mt-3 space-y-1">
            <button
              type="button"
              class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:text-red-800 hover:bg-gray-100"
              @click="() => {
                showLogoutModal.value = true;
                showingNavigationDropdown.value = false;
              }"
            >
              Keluar
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <slot />
        </div>
      </div>
    </main>
  </div>

  <div
    v-if="showLogoutModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
  >
    <div class="bg-white rounded-xl shadow-2xl w-80 p-6 transform transition-all scale-100">
      <div class="flex justify-center mb-4">
        <div class="p-3 bg-red-100 rounded-full">
          <ExclamationCircleIcon class="w-8 h-8 text-red-500" />
        </div>
      </div>
      <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Konfirmasi Keluar</h3>
      <p class="text-center text-gray-500 text-sm mb-6">Apakah Anda yakin ingin mengakhiri sesi ini?</p>

      <div class="flex justify-center gap-3">
        <button
          type="button"
          class="flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors"
          @click="showLogoutModal = false"
        >
          Batal
        </button>
        <button
          type="button"
          class="flex-1 px-4 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600 transition-colors shadow-md"
          @click="handleLogout"
        >
          Ya, Keluar
        </button>
      </div>
    </div>
  </div>
</template>

