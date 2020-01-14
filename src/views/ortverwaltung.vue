<template>
    <div>
        <md-table class="md-layout-item md-size-70 md-small-size-100" v-model="searched" md-sort="ort"
                  md-sort-order="asc" md-card md-fixed-header>
            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <div>
                        <h2 class="md-title">Ortverwaltung</h2>
                        <md-button class="md-raised md-primary">add</md-button>
                    </div>
                </div>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name or plz..." v-model="search" @input="searchOnTable"/>
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state
                    md-label="No place found"
                    :md-description="`No place found for this '${search}' query. Try a different search term or add new place.`">
                <md-button class="md-primary md-raised" @click="newUser">Add New Place</md-button>
            </md-table-empty-state>

            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell class="item-options">
                    <md-button class="md-icon-button">
                        <md-icon>edit</md-icon>
                    </md-button>
                    <md-button class="md-icon-button">
                        <md-icon>delete</md-icon>
                    </md-button>
                </md-table-cell>
                <md-table-cell md-label="Plz" md-sort-by="plz" md-numeric>{{ item.plz }}</md-table-cell>
                <md-table-cell md-label="Ort" md-sort-by="ort">{{ item.ort }}</md-table-cell>
            </md-table-row>
        </md-table>
    </div>
</template>

<script>
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
        data: () => ({
            position: 'center',
            duration: 4000,
            isInfinity: false,
            snackbarMessage: '',
            showSnackbar: false,

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
            }]
        }),
        mounted() {
            this.axios
                .post("/api/index.php", {
                    id: "ort",
                    func: "read"
                })
                .then(response => {
                    this.setTableList(response.data)
                })
                .catch(error => {
                    console.log(error);
                });
        },
        methods: {
            setTableList(list) {
                this.ortList = list;
                this.searched = list;
            },
            newUser() {
                window.alert('Noop')
            },
            searchOnTable() {
                this.searched = searchByName(this.ortList, this.search)
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
</style>