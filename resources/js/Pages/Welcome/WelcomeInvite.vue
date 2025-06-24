<template>
    <div class="welcome">
        <welcome-invite />
        <main-footer />
    </div>
</template>
<script>
import { WelcomeInvite,MainFooter } from "@/components/index";

export default {
    components: {
        WelcomeInvite,
        MainFooter
    },
}
</script>

<style scoped>
.welcome{
    background-color: #F9FAFF;
}
</style>