<template>
  <div>
    <form novalidate class="md-layout" @submit.prevent="validateUser">
      <md-card class="md-layout-item md-size-50 md-small-size-100">
        <md-card-header>
          <div class="md-title">Landverwaltung</div>
        </md-card-header>

        <md-card-content>
          <div class="md-layout md-gutter">
            <div class="md-layout-item md-small-size-100">
              <md-field :class="getValidationClass('firstName')">
                <label for="land">Land</label>
                <md-input
                  name="lande"
                  id="land"
                  autocomplete="given-name"
                  v-model="landform.land"
                  :disabled="sending"
                />
                <span class="md-error" v-if="!$v.landform.land.required"
                  >A Landname is required</span
                >
                <span
                  class="md-error"
                  v-else-if="this.invalidInputs.includes('land')"
                  >Invalid Landname</span
                >
              </md-field>
            </div>
          </div>
        </md-card-content>
        <div class="md_button">
          <md-button class="md-raised md-primary">suchen</md-button>
          <md-button class="md-raised" v-on:click="newElement()">neu</md-button>
          <md-button class="md-raised md-primary" type="submit">{{
            land.lid === null ? "speichern" : "update"
          }}</md-button>
          <md-button class="md-raised md-accent" v-on:click="deletePerson()"
            >löschen</md-button
          >
        </div>
      </md-card>

      <md-snackbar :md-active.sync="landSaved"
        >The Land {{ land }} was saved with success!</md-snackbar
      >
    </form>
  </div>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import {
    required,
    email,
    minLength

  } from 'vuelidate/lib/validators'

  export default {
    name: 'FormValidation',
    mixins: [validationMixin],
    data: () => ({
      invalidInputs:[],
      landList:[],
      landform:{
      land:null

      }),
     landSaved: false,
     sending: false,
     
    }),
    validations: {
      landform: {
        land: {
          required,
          minLength: minLength(3)
        }
      }
    },
    mounted(){
      this.getLandList();
    },
    methods: {
      getValidationClass (fieldName) {
        const field = this.$v.landform[fieldName];

        if (field) {
          return {
            'md-invalid': field.$invalid && field.$dirty || this.invalidInputs.includes(fieldName)
          }
        }
      },
      getLandList(){
        this.axios.post("/api/index.php",{
          id:"land",
          func:"readList"
        })
        .then(response =>{
          this.landList = response.data;
          this.first();
        }).catch(error =>{
          console.log(error)
          
        });
      },
      clearForm () {
        this.$v.$reset();
        this.formland ={
          lid:null,
          land:null
        };
     
      },
      deleteLand () {
        if(this.formland.lid){
          this.axios.post('/api/index.php',{
            id:'land',
            func:'delete',
            landform: this.landform.lid
          }
        )
        }
        this.getLandList();
      },
      saveLand(){
        this.axios.post('/api/index.php',{
          id:'land',
          func:'speichern',
          landform:this.landform
        }).then(response=>{
          console.log(response.data);
          if(response.data.status){
            this.landList = response.data.data;
          }else{
            this.invalidInputs = response.data.message;
          }
        });
      }
      validateUser () {
        this.$v.$touch();

        if (!this.$v.$invalid) {
          this.saveLand()
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
.md-progress-bar {
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
}
.md_button {
  padding-left: 50px;
  padding-bottom: 25px;
}
</style>
