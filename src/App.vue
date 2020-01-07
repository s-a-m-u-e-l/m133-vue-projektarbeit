<template>
  <div class="page-container">
    <md-app>
      <md-app-toolbar class="md-primary">
        <span class="md-title">Adressverwaltung</span>
      </md-app-toolbar>

      <md-app-drawer md-permanent="full">
        <md-toolbar class="md-transparent" md-elevation="0">
          Navigation
        </md-toolbar>

        <md-list>
        <router-link to="/personenverwaltung">
          <md-list-item>
            <md-icon>people</md-icon>
            <span class="md-list-item-text">Perosnonenverwaltung</span>
             </md-list-item>
            </router-link>
         

          <md-list-item>
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
  </div>
</template>

<style lang="scss" scoped>
  .md-app {
    max-height: 400px;
    border: 1px solid rgba(#000, .12);
  }

   // Demo purposes only
  .md-drawer {
    width: 230px;
    max-width: calc(100vw - 125px);
  }

</style>

<script>
export default {
  name: 'Normal'
}
</script>