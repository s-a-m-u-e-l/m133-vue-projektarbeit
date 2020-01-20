<template>
    <md-app class="page-container" md-waterfall md-mode="fixed">
        <md-app-toolbar class="md-primary">
            <md-button class="md-icon-button" @click="menuVisible = !menuVisible">
                <md-icon>menu</md-icon>
            </md-button>
            <span class="md-title">Adressverwaltung</span>
        </md-app-toolbar>

        <md-app-drawer class="sidenav" md-permanent="full" :md-active.sync="menuVisible">
            <md-toolbar class="md-transparent" md-elevation="0">
                Navigation
            </md-toolbar>

            <md-list>
                <md-list-item to="/personenverwaltung">
                    <md-icon>people</md-icon>
                    <span class="md-list-item-text">Persononenverwaltung</span>
                </md-list-item>

                <md-list-item  to="/ortverwaltung">
                    <md-icon>landscape</md-icon>
                    <span class="md-list-item-text">Ortverwaltung</span>
                </md-list-item>

                <md-list-item to="/landverwaltung">
                    <md-icon>language</md-icon>
                    <span class="md-list-item-text">Landverwaltung</span>
                </md-list-item>
            </md-list>
        </md-app-drawer>

        <md-app-content>
            <router-view/>
        </md-app-content>
    </md-app>
</template>

<style lang="scss" scoped>
    .md-app {
        border: 1px solid rgba(#000, 0.12);
    }

    .sidenav {
        width: auto;
    }

    .page-container {
        height: 100vh;
    }

</style>

<script>
    export default {
        name: "Normal",
        data: () => ({
            menuVisible: false
        })
    };
</script>
