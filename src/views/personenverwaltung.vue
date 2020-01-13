<template>
    <div>
        <form v-if="personList && ortList && landList" novalidate class="md-layout" @submit.prevent="validateUser()">
            <md-card class="md-layout-item md-size-70 md-small-size-100">
                <md-card-header>
                    <div class="md-title">Personenverwaltung</div>
                </md-card-header>

                <md-card-content>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('name')">
                                <label for="first-name">Name</label>
                                <md-input name="first-name" id="first-name" autocomplete="given-name"
                                          v-model="person.name" :disabled="sending"/>
                                <span class="md-error"
                                      v-if="!this.$v.person.name.required">The first name is required</span>
                                <span class="md-error"
                                      v-else-if="this.invalidInputs.includes('name')">Invalid name</span>
                            </md-field>
                        </div>

                        <div class="md-layout-item md-small-size-100">
                            <md-field :class="getValidationClass('vorname')">
                                <label for="last-name">Vorname</label>
                                <md-input name="last-name" id="last-name" autocomplete="family-name"
                                          v-model="person.vorname" :disabled="sending"/>
                                <span class="md-error"
                                      v-if="!this.$v.person.vorname.required">The last name is required</span>
                                <span class="md-error"
                                      v-else-if="this.invalidInputs.includes('vorname')">Invalid first name</span>
                            </md-field>
                        </div>
                    </div>

                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('strasse')">
                            <label for="strasse">Strasse</label>
                            <md-input name="last-name" id="strasse" autocomplete="street-address"
                                      v-model="person.strasse"
                                      :disabled="sending"/>
                            <span class="md-error"
                                  v-if="!this.$v.person.strasse.required">The street name is required</span>
                        </md-field>
                    </div>
                    <div class="md-layout md-gutter">
                        <div class="md-layout-item md-small-size-50">
                            <md-field :class="getValidationClass('oid')">
                                <label>Ort</label>
                                <md-select name="gender" v-model="person.oid" md-dense :disabled="sending">
                                    <md-option v-for="ort in ortList" v-bind:key="ort.oid" :value="ort.oid">
                                        {{ort.plz+' '+ort.ort}}
                                    </md-option>
                                </md-select>
                                <span class="md-error" v-if="!this.$v.person.oid.required">Selection required</span>
                            </md-field>
                        </div>
                        <div class="md-layout-item md-small-size-50">
                            <md-field :class="getValidationClass('lid')">
                                <label for="land">Land</label>
                                <md-select name="gender" id="land" v-model="person.lid" md-dense :disabled="sending">
                                    <md-option v-for="land in landList" v-bind:key="land.lid" :value="land.lid">
                                        {{land.land}}
                                    </md-option>
                                </md-select>
                                <span class="md-error" v-if="!this.$v.person.lid.required">Selection required</span>
                            </md-field>
                        </div>
                    </div>

                    <md-field :class="getValidationClass('email')">
                        <label for="email">Email</label>
                        <md-input type="email" name="email" id="email" autocomplete="email" v-model="person.email"
                                  :disabled="sending"/>
                        <span class="md-error" v-if="!this.$v.person.email.required">The email is required</span>
                        <span class="md-error" v-else-if="!this.$v.person.email.email">Invalid email</span>
                    </md-field>
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('tel_priv')">
                            <label for="telefon">Telefon Privat</label>
                            <md-input name="last-name" id="telefon" autocomplete="tel" v-model="person.tel_priv"
                                      :disabled="sending"/>
                            <span class="md-error" v-if="!this.$v.person.tel_priv.minLength">Invalid phone number</span>
                        </md-field>
                    </div>
                    <div class="md-layout-item md-small-size-100">
                        <md-field :class="getValidationClass('tel_gesch')">
                            <label for="tel-gesch">Telefon Geschäft</label>
                            <md-input name="last-name" id="tel-gesch" autocomplete="tel"
                                      v-model="person.tel_gesch" :disabled="sending"/>
                            <span class="md-error"
                                  v-if="!this.$v.person.tel_gesch.minLength">Invalid phone number</span>
                        </md-field>
                    </div>
                </md-card-content>
                <div>
                    <span class="page">Seite {{personIndex + 1}} von {{personList.length}}</span>
                </div>
                <div class="action-buttons">
                    <div class="switch-page">
                        <md-button class="md-icon-button md-raised md-primary md-mini first" v-on:click="first()"
                                   :disabled="previousButtonDis">
                            <md-icon>first_page</md-icon>
                        </md-button>
                        <md-button class="md-icon-button md-raised md-primary previous" v-on:click="previous()"
                                   :disabled="previousButtonDis">
                            <md-icon>navigate_before</md-icon>
                        </md-button>
                        <md-button class="md-icon-button md-raised md-primary next" v-on:click="next()"
                                   :disabled="nextButtonDis">
                            <md-icon>navigate_next</md-icon>
                        </md-button>
                        <md-button class="md-icon-button md-raised md-primary last" v-on:click="last()"
                                   :disabled="nextButtonDis">
                            <md-icon>last_page</md-icon>
                        </md-button>
                    </div>

                    <div class="md_button">
                        <md-button class="md-raised md-accent" v-on:click="deletePerson()">löschen</md-button>
                        <md-button class="md-raised" v-on:click="newElement()">neu</md-button>
                        <md-button class="md-raised md-primary" type="submit">{{person.pid === null ? 'speichern':'update'}}</md-button>
                    </div>
                </div>
            </md-card>

            <md-snackbar :md-active.sync="userSaved">The user {{ lastUser }} was saved with success!</md-snackbar>
            <md-snackbar :md-position="position" :md-duration="isInfinity ? Infinity : duration" :md-active.sync="showSnackbar" md-persistent>
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
        name: 'FormValidation',
        mixins: [validationMixin],
        data: () => ({
            position: 'center',
            duration: 4000,
            isInfinity: false,
            snackbarMessage: '',
            showSnackbar: false,

            invalidInputs: [],
            nextButtonDis: false,
            previousButtonDis: false,
            personIndex: 0,
            personList: [],
            person: {
                pid: null,
                name: null,
                vorname: null,
                strasse: null,
                oid: null,
                lid: null,
                email: null,
                tel_priv: null,
                tel_gesch: null
            },
            ortList: [{
                oid: null,
                plz: null,
                ort: null
            }],
            landList: [{
                lid: null,
                land: null
            }],
            userSaved: false,
            sending: false,
            lastUser: null
        }),
        validations: {
            person: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                vorname: {
                    required,
                    minLength: minLength(3)
                },
                strasse: {
                    required
                },
                oid: {
                    required
                },
                lid: {
                    required
                },
                email: {
                    required,
                    email
                },
                tel_priv: {
                    minLength: minLength(10)
                },
                tel_gesch: {
                    minLength: minLength(10)
                }
            }
        },
        mounted() {
            this.getPersonList();
            this.axios
                .post("/api/index.php", {
                    id: "ort",
                    func: "read"
                })
                .then(response => {
                    this.ortList = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
            this.axios
                .post("/api/index.php", {
                    id: "land",
                    func: "read"
                })
                .then(response => {
                    this.landList = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        methods: {
            checkout(index) {
                this.personIndex = index;
                this.person = this.personList[this.personIndex];
                this.checkNextPrevButton();
            },
            next() {
                if (this.personIndex !== (this.personList.length - 1) && this.personIndex < this.personList.length) {
                    this.personIndex++;
                    this.person = this.personList[this.personIndex];
                    this.checkNextPrevButton();
                }
            },
            previous() {
                if (this.personIndex > 0) {
                    this.personIndex--;
                    this.person = this.personList[this.personIndex];
                    this.checkNextPrevButton();
                }
            },
            first() {
                this.personIndex = 0;
                this.person = this.personList[this.personIndex];
                this.checkNextPrevButton();
            },
            last() {
                this.personIndex = this.personList.length - 1;
                this.person = this.personList[this.personIndex];
                this.checkNextPrevButton();
            },
            checkNextPrevButton() {
                this.nextButtonDis = false;
                this.previousButtonDis = false;
                if (this.personIndex === (this.personList.length - 1)) {
                    this.nextButtonDis = true;
                }
                if (this.personIndex === 0) {
                    this.previousButtonDis = true;
                }
            },
            getValidationClass(fieldName) {
                const field = this.$v.person[fieldName];

                if (field) {
                    return {
                        'md-invalid': field.$invalid && field.$dirty || this.invalidInputs.includes(fieldName)
                    }
                }
            },
            getPersonList() {
                this.axios
                    .post("/api/index.php", {
                        id: "person",
                        func: "readList"
                    })
                    .then(response => {
                        this.personList = response.data;
                        if (this.personList.length !== 0) {
                            this.first();
                        } else {
                            this.newElement();
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            newElement() {
                this.clearForm();
                this.personList.push(this.person);
                this.last();
            },
            clearForm() {
                this.$v.$reset();
                this.person = {
                    pid: null,
                    name: null,
                    vorname: null,
                    strasse: null,
                    oid: null,
                    lid: null,
                    email: null,
                    tel_priv: null,
                    tel_gesch: null
                };
            },
            deletePerson() {
                if (this.person.pid) {
                    this.axios.post('/api/index.php',
                        {
                            id: 'person',
                            func: 'delete',
                            pid: this.person.pid
                        }
                    ).then(response => {
                        if (response.data.message === 'deleted') {
                            this.personList = response.data.data;
                            if (this.personList.length === 0) {
                                this.newElement();
                            } else {
                                this.checkout(this.personIndex - 1);
                            }
                            this.openSnackbar('person successfully deleted');
                        }
                    })
                }
            },
            savePerson() {
                this.axios.post('/api/index.php',
                    {
                        id: 'person',
                        func: 'save',
                        person: this.person
                    }
                ).then(response => {
                    if (response.data.status) {
                        this.personList = response.data.data;
                        if (response.data.message === 'saved') {
                            this.last();
                            this.openSnackbar('person successfully saved');
                        } else if (response.data.message === 'updated') {
                            this.openSnackbar('person successfully updated');
                        }
                    } else {
                        this.invalidInputs = response.data.message;
                    }
                });
            },
            validateUser() {
                this.$v.$touch();
                if (!this.$v.$invalid) {
                    this.savePerson()
                }
            },
            openSnackbar(message) {
                this.snackbarMessage = message;
                this.showSnackbar = true;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .page {
        user-select: none;
        margin: 6px 8px;
    }

    .action-buttons {
        user-select: none;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        padding-bottom: 25px;
    }

    .switch-page {
        display: flex;
    }

    .md-progress-bar {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
    }

    .md_button {
        display: flex;
        flex-wrap: wrap;
    }
</style>