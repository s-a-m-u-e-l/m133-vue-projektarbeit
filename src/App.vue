<template>
    <md-app class="page-container">
        <md-app-toolbar class="md-primary">
            <span class="md-title">Adressverwaltung</span>
        </md-app-toolbar>

        <md-app-drawer class="sidenav" md-permanent="full">
            <md-toolbar class="md-transparent" md-elevation="0">
                Navigation
            </md-toolbar>

            <md-list>
                <md-list-item to="/personenverwaltung">
                    <md-icon>people</md-icon>
                    <span class="md-list-item-text">Perosnonenverwaltung</span>
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

    // Demo purposes only
    .md-drawer {
        width: 230px;
        max-width: calc(100vw - 125px);
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
        name: "Normal"
    };
</script>
