<template>
    <div>
        <form novalidate class="md-layout" @submit.prevent="validateUser">
            <md-card class="md-layout-item md-size-40 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Landverwaltung</div>
                </md-card-header>

                <md-card-content>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('land')">
                                <label for="land">Land</label>
                                <md-input
                                        name="lande"
                                        id="land"
                                        autocomplete="disabled"
                                        v-model="land.land"/>
                                <span class="md-error" v-if="!this.$v.land.land.required">A Landname is required</span>
                                <span
                                        class="md-error"
                                        v-else-if="this.invalidInputs.includes('land')">Invalid Landname</span>
                            </md-field>
                        </div>
                    </div>
                </md-card-content>
                <div class="md_button">
                    <md-button class="md-raised md-accent" v-on:click="deleteLand()">löschen</md-button>
                    <md-button class="md-raised" v-on:click="searchLand()">suchen</md-button>
                    <md-button class="md-raised" v-on:click="newElement()">neu</md-button>
                    <md-button class="md-raised md-primary" type="submit" :disabled="this.$v.$invalid">{{
                        land.lid === null ? "speichern" : "update"
                        }}
                    </md-button>
                </div>
            </md-card>
            <md-table class="md-layout-item md-size-50 md-small-size-100" v-model="landList" md-card
                      @md-selected="onSelect">
                <md-table-toolbar>
                    <h1 class="md-title">Land Liste</h1>
                </md-table-toolbar>
                <md-table-row class="md-primary" slot="md-table-row" slot-scope="{ item }" md-selectable="single">
                    <md-table-cell md-label="Land">{{ item.land }}</md-table-cell>
                </md-table-row>
            </md-table>

            <md-snackbar :md-position="position" :md-duration="isInfinity ? Infinity : duration"
                         :md-active.sync="showSnackbar" md-persistent>
                <span>{{snackbarMessage}}</span>
                <md-button class="md-primary" @click="showSnackbar = false">ok</md-button>
            </md-snackbar>
        </form>
    </div>
</template>

<script>
    import {validationMixin} from 'vuelidate'
    import {
        required,
        email,
        minLength

    } from 'vuelidate/lib/validators'

    export default {
        name: 'LandVerwaltung',
        mixins: [validationMixin],
        data: () => ({
            selected: {},
            position: 'center',
            duration: 4000,
            isInfinity: false,
            snackbarMessage: '',
            showSnackbar: false,

            invalidInputs: [],
            landList: [],
            land: {
                lid: null,
                land: null,
            },
        }),
        validations: {
            land: {
                land: {
                    required,
                    minLength: minLength(3)
                }
            }
        },
        mounted() {
            this.getLandList();
        },
        methods: {
            searchLand() {
                if (this.land.land != null && this.land.land !== '') {
                    this.landList = this.landList.filter(value => value.land.toLowerCase().includes(this.land.land.toLowerCase()));
                } else {
                    this.getLandList();
                }
            },
            getValidationClass(fieldName) {
                const field = this.$v.land[fieldName];

                if (field) {
                    return {
                        'md-invalid': field.$invalid && field.$dirty || this.invalidInputs.includes(fieldName)
                    }
                }
            },
            newElement() {
                this.clearForm();
            },
            getLandList() {
                this.axios.post("/api/index.php", {
                    id: "land",
                    func: "readList"
                })
                    .then(response => {
                        this.landList = response.data;
                    }).catch(error => {
                    console.log(error)
                });
            },
            clearForm() {
                this.$v.$reset();
                this.land = {
                    lid: null,
                    land: null
                };
            },
            saveLand() {
                if (this.land.land) {
                    this.axios.post('/api/index.php', {
                        id: 'land',
                        func: 'save',
                        land: this.land
                    }).then(response => {
                        if (response.data.status) {
                            this.landList = response.data.data;
                            this.openSnackbar('land successfully saved');
                            this.clearForm();
                        } else {
                            this.invalidInputs = response.data.message;
                        }
                    });
                }
            },
            validateUser() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.saveLand();
                }
            },
            openSnackbar(message) {
                this.snackbarMessage = message;
                this.showSnackbar = true;
            },
            onSelect(item) {
                if (item) {
                    this.land = item;
                } else {
                    this.clearForm();
                }
            },
            deleteLand() {
                if (this.land.lid != null) {
                    this.axios.post('/api/index.php',
                        {
                            id: 'land',
                            func: 'delete',
                            lid: this.land.lid
                        }
                    ).then(response => {
                        if (response.data.message === 'deleted') {
                            this.landList = response.data.data;
                            this.openSnackbar('person successfully deleted');
                            this.clearForm();
                        }
                    })
                } else {
                    this.clearForm();
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .card-land-liste {
        padding: 10px;
    }

    .md-progress-bar {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
    }

    .md_button {
        display: flex;
        justify-content: center;
        padding-bottom: 25px;
        flex-wrap: wrap;
    }
</style>
