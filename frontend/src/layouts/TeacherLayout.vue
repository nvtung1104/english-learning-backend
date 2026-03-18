<template>
  <div class="min-h-screen bg-[#f4f2ee] flex">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-40 flex flex-col admin-sidebar transition-transform duration-300 xl:static xl:translate-x-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full xl:translate-x-0',
        collapsed ? 'w-[68px]' : 'w-[260px]'
      ]"
    >
      <TeacherSidebar :collapsed="collapsed" @toggle="collapsed = !collapsed" @navigate="sidebarOpen = false" />
    </aside>

    <!-- Main -->
    <div class="flex flex-col flex-1 min-w-0 min-h-screen">
      <TeacherTopbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />
      <main class="flex-1 px-4 pb-8 pt-5 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-[1600px]">
          <RouterView />
        </div>
      </main>
    </div>

    <!-- Mobile overlay -->
    <Transition name="fade">
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 z-30 bg-navy/40 backdrop-blur-sm xl:hidden"
        @click="sidebarOpen = false"
      />
    </Transition>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRoute, RouterView } from 'vue-router'
import TeacherSidebar from '../components/teacher/TeacherSidebar.vue'
import TeacherTopbar from '../components/teacher/TeacherTopbar.vue'

const sidebarOpen = ref(false)
const collapsed = ref(false)
const route = useRoute()

watch(() => route.fullPath, () => { sidebarOpen.value = false })
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
