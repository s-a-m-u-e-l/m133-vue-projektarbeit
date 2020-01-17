<template>
    <v-dialog>
        <template v-slot:activator="{ on }"><v-btn v-on="on">Test</v-btn></template>
        <v-card>
            <v-card-title>
                <h2>Add a new Place</h2>
            </v-card-title>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Dialog",
        data: () => ({
            dialog: true,
        }),
        methods: {
        }
    }
</script>

<style scoped>

</style>