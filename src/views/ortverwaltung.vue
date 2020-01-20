<template>
    <div>
        <md-table class="md-layout-item md-size-100 md-small-size-100" v-model="searched" md-sort="ort"
                  md-sort-order="asc" md-card md-fixed-header>
            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <div>
                        <h2 class="md-title">Ortverwaltung</h2>
                        <div class="forms">
                            <md-field v-if="openForm" class="form-plz" md-clearable  :class="getValidationClass('plz')">
                                <md-input v-on:change="this.invalidInputs = []" placeholder="PLZ" v-model="ort.plz" @input="searchOnTable"/>
                                <span class="md-error" v-if="!this.$v.ort.plz.required">A post code is required</span>
                                <span class="md-error" v-if="!this.$v.$invalid && this.invalidInputs.includes('plz')">Invalid syntax</span>
                            </md-field>
                            <md-field v-if="openForm" md-clearable class="form-ort"  :class="getValidationClass('ort')">
                                <md-input placeholder="Ort" v-model="ort.ort" @input="searchOnTable"/>
                                <span class="md-error" v-if="!this.$v.ort.ort.required">A country name is required</span>
                                <span class="md-error" v-if="!this.$v.$invalid && this.invalidInputs.includes('ort')">Invalid syntax</span>
                            </md-field>
                            <md-button class="md-raised" :class="openForm ? '':'md-primary'" v-on:click="toggleForm">{{openForm ? 'close':'add'}}</md-button>
                            <md-button v-if="openForm" class="md-raised md-primary" v-on:click="validate()">{{ort.oid ? 'update':'save'}}</md-button>
                        </div>
                    </div>
                </div>
                <md-snackbar :md-position="position" :md-duration="isInfinity ? Infinity : duration"
                             :md-active.sync="showSnackbar" md-persistent>
                    <span>{{snackbarMessage}}</span>
                    <md-button class="md-primary" @click="showSnackbar = false">ok</md-button>
                </md-snackbar>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name or plz..." class="md-size-20" v-model="search"
                              @input="searchOnTable"/>
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state
                    md-label="No place found"
                    :md-description="`No place found for this '${search}' query. Try a different search term or add new place.`">
                <md-button class="md-primary md-raised" @click="openForm = true">Add New Place</md-button>
            </md-table-empty-state>

            <md-table-row slot="md-table-row" slot-scope="{ item }">

                <md-table-cell md-label="PLZ Ort" md-sort-by="ort">{{item.plz}} {{ item.ort }}</md-table-cell>
                <md-table-cell class="row-options">
                    <md-button class="md-icon-button" @click="editOrt(item)">
                        <md-icon>edit</md-icon>
                    </md-button>
                    <md-button class="md-icon-button" @click="deleteOrt(item.oid)">
                        <md-icon>delete</md-icon>
                    </md-button>
                </md-table-cell>
            </md-table-row>
        </md-table>
    </div>
</template>

<script>
    import {validationMixin} from 'vuelidate'
    import {
        required,
        minLength,
        integer

    } from 'vuelidate/lib/validators'

    const toLower = text => {
        return text.toString().toLowerCase()
    };

    const searchByName = (items, term) => {
        if (term) {
            return items.filter(item => toLower(item.ort).includes(toLower(term)) || toLower(item.plz).includes(toLower(term)))
        }

        return items
    };

    export default {
        name: 'OrtVerwaltung',
        mixins: [validationMixin],
        data: () => ({
            position: 'center',
            duration: 4000,
            isInfinity: false,
            snackbarMessage: '',
            showSnackbar: false,
            openForm: false,

            invalidInputs: [],
            search: null,
            searched: [],
            users: [],
            ort: {
                oid: null,
                ort: null,
                plz: null
            },
            ortList: [{
                oid: null,
                ort: null,
                plz: null
            }],
            columns: [
                {
                    label: 'PLZ',
                    field: 'plz',
                },
                {
                    label: 'Ort',
                    field: 'ort',
                }
            ],
        }),
        validations: {
            ort: {
                plz: {
                    required
                },
                ort: {
                    required
                }
            }
        },
        mounted() {
            this.loadOrtData();
        },
        methods: {
            loadOrtData() {
                this.axios
                    .post("/api/index.php", {
                        id: "ort",
                        func: "read"
                    })
                    .then(response => {
                        this.setTableList(response.data);
                        this.searchOnTable();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            toggleForm() {
                this.openForm = !this.openForm;
                this.clearForm();
            },
            editOrt(item) {
                this.openForm = true;
                this.ort = item;
            },
            getValidationClass(fieldName) {
                const field = this.$v.ort[fieldName];

                if (field) {
                    return {
                        'md-invalid': field.$invalid && field.$dirty || this.invalidInputs.includes(fieldName)
                    }
                }
            },
            validate() {
                this.$v.$touch();
                if (!this.$v.$invalid) {
                    this.save();
                }
            },
            openSnackbar(message) {
                this.snackbarMessage = message;
                this.showSnackbar = true;
            },
            save() {
                this.axios.post('/api/index.php', {
                    id: 'ort',
                    func: 'save',
                    ort: this.ort
                }).then(response => {
                    if (response.data.status) {
                        this.ortList = response.data.data;
                        this.openSnackbar('ort successfully saved');
                        this.clearForm();
                    } else {
                        this.invalidInputs = response.data.message;
                    }
                });
            },
            clearForm() {
                this.$v.$reset();
                this.ort = {
                    oid: null,
                    ort: null,
                    plz: null
                };
                this.invalidInputs = [];
                this.loadOrtData();
            },
            setTableList(list) {
                this.ortList = list;
                this.searched = list;
            },
            searchOnTable() {
                this.searched = searchByName(this.ortList, this.search)
            },
            deleteOrt(oid) {
                this.axios
                    .post("/api/index.php", {
                        id: "ort",
                        func: "delete",
                        oid: oid
                    })
                    .then(response => {
                        if (response.data.status) {
                            this.setTableList(response.data.data)
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        created() {
            this.searched = this.ortList;
        }
    }
</script>

<style lang="scss" scoped>
    .md-field {
        max-width: 300px;
    }

    .form-invisible {
        display: none;
    }

    .form-visible {
        display: block;
    }

    .row-options {
        width: 176px;
    }

    .forms {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;

        .form-ort, .form-plz {
            margin: 0 10px 0 0;
        }

        .form-plz {
            margin-left: 8px;
        }
    }

    .search-form {
        width: 20px;
    }
</style>