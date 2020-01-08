<template>
    <!--  eslint-disable  -->
    <div>
        <form novalidate class="md-layout" @submit.prevent="saveUser()">
            <md-card class="md-layout-item md-size-50 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Personenverwaltung</div>
                </md-card-header>

                <md-card-content>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('firstName')">
                                <label for="first-name">Name</label>
                                <md-input name="first-name" id="first-name" autocomplete="given-name"
                                          v-model="person.firstName" :disabled="sending"/>
                                <span class="md-error"
                                      v-if="!$v.person.firstName.required">The first name is required</span>
                                <span class="md-error"
                                      v-else-if="!$v.person.firstName.minlength">Invalid first name</span>
                            </md-field>
                        </div>

                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('lastName')">
                                <label for="last-name">Vorname</label>
                                <md-input name="last-name" id="last-name" autocomplete="family-name"
                                          v-model="person.lastName" :disabled="sending"/>
                                <span class="md-error"
                                      v-if="!$v.person.lastName.required">The last name is required</span>
                                <span class="md-error"
                                      v-else-if="!$v.person.lastName.minlength">Invalid last name</span>
                            </md-field>
                        </div>
                    </div>

                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('strasse')">
                            <label for="strasse">Strasse</label>
                            <md-input name="last-name" id="strasse" autocomplete="street" v-model="person.strasse"
                                      :disabled="sending"/>
                            <span class="md-error" v-if="!$v.person.strasse.required">The street name is required</span>
                        </md-field>
                    </div>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-50">
                            <md-field :class="getValidationClass('oid')">
                                <label for="ort">Ort</label>
                                <md-select name="gender" id="ort" v-model="person.oid" md-dense :disabled="sending">
                                    <md-option value="">Select</md-option>
                                    <md-option v-for="ort in ortList" :value="ort.oid" v-text="ort.plz+' '+ort.ort"></md-option>
                                </md-select>
                                <span class="md-error">The gender is required</span>
                            </md-field>
                        </div>


                        <div class="md-layout-item md-small-size-50">
                            <md-field :class="getValidationClass('land')">
                                <label for="land">Land</label>
                                <md-select name="gender" id="land" v-model="person.gender" md-dense :disabled="sending">
                                    <md-option></md-option>
                                    <md-option value="M">M</md-option>
                                    <md-option value="F">F</md-option>
                                </md-select>
                                <span class="md-error">The gender is required</span>
                            </md-field>
                        </div>
                    </div>

                    <md-field :class="getValidationClass('email')">
                        <label for="email">Email</label>
                        <md-input type="email" name="email" id="email" autocomplete="email" v-model="person.email"
                                  :disabled="sending"/>
                        <span class="md-error" v-if="!$v.person.email.required">The email is required</span>
                        <span class="md-error" v-else-if="!$v.person.email.email">Invalid email</span>
                    </md-field>
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('telPriv')">
                            <label for="telefon">Telefon Privat</label>
                            <md-input name="last-name" id="telefon" autocomplete="family-name" v-model="person.lastName"
                                      :disabled="sending"/>
                            <span class="md-error" v-if="!$v.person.lastName.required">The last name is required</span>
                            <span class="md-error" v-else-if="!$v.person.lastName.minlength">Invalid last name</span>
                        </md-field>
                    </div>
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('telGesch')">
                            <label for="tel-gesch">Telefon Geschäft</label>
                            <md-input name="last-name" id="tel-gesch" autocomplete="family-name"
                                      v-model="person.lastName" :disabled="sending"/>
                            <span class="md-error" v-if="!$v.person.lastName.required">The last name is required</span>
                            <span class="md-error" v-else-if="!$v.person.lastName.minlength">Invalid last name</span>
                        </md-field>
                    </div>
                </md-card-content>

                <div class="md_button">

                    <md-button class="md-raised md-primary">suchen</md-button>
                    <md-button class="md-raised md-primary">neu</md-button>
                    <md-button class="md-raised md-primary" type="submit">speichern</md-button>
                    <md-button class="md-raised md-accent">löschen</md-button>
                </div>
            </md-card>

            <md-snackbar :md-active.sync="userSaved">The user {{ lastUser }} was saved with success!</md-snackbar>
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
    /* eslint-disable */
    export default {
        name: 'FormValidation',
        mixins: [validationMixin],
        data: () => ({
            person: {
                firstName: null,
                lastName: null,
                strasse: null,
                oid: 0,
                email: null,
                telPriv: null,
                telGesch: null
            },
            ortList: [{
                oid: 0,
                plz: 0,
                ort: null
            }],
            userSaved: false,
            sending: false,
            lastUser: null
        }),
        validations: {
            person: {
                firstName: {
                    required,
                    minLength: minLength(3)
                },
                lastName: {
                    required,
                    minLength: minLength(3)
                },
                strasse: {
                    required
                },
                oid: {
                    required
                },
                email: {
                    required,
                    email
                },
                telPriv: {
                    required
                },
                telGesch: {
                    required
                }
            }
        },
        mounted() {
            this.axios
                .post("/api/index.php", {
                    id: "ort",
                    func: "lesen"
                })
                .then(response => {
                    console.log(response);
                    this.ortList = response.data.data;
                    console.log(this.ortList);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        methods: {
            getValidationClass(fieldName) {
                const field = this.$v.person[fieldName];

                if (field) {
                    return {
                        'md-invalid': field.$invalid && field.$dirty
                    }
                }
            },
            clearForm() {
                this.$v.$reset();
                this.person = {
                    firstName: null,
                    lastName: null,
                    strasse: null,
                    oid: 0,
                    email: null,
                    telPriv: null,
                    telGesch: null
                };
            },
            saveUser() {
                this.$v.$touch();
                this.axios.post('/api/index.php',
                    {
                        id: 'person',
                        func: 'speichern',
                        person: this.person
                    }
                ).then(response => {
                    console.log(response.data);
                });
            },
            validateUser() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.saveUser()
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