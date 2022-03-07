<template>
  <v-card>
    <v-toolbar flat class="bg-primary">
      <v-toolbar-title>{{ reportTitle }}</v-toolbar-title>
    </v-toolbar>
    <v-tabs vertical>
      <v-tab>
        <v-icon left class="icon-color"> mdi-checkbox-marked-circle</v-icon>
        Reporte acumulado
      </v-tab>
      <v-tab>
        <v-icon left class="icon-color"> mdi-checkbox-marked-circle</v-icon>
        Reporte mensual
      </v-tab>
      <v-tab-item>
        <v-card flat>
          <v-card-title class="">Reporte acumulado por acciones</v-card-title>
          <v-card-text>
            <v-col cols="12" sm="12" md="12">
              <base-select label="Institución:" />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select label="Unidad organizativa:" />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select label="Año:" />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select label="Mes:" />
            </v-col>
          </v-card-text>
          <v-col align="start">
            <v-btn class="btn-normal-close" rounded> Generar reporte </v-btn>
          </v-col>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <v-card flat>
          <v-card-text>
            <v-card-title class=""
              >Reporte mensual de seguimientos</v-card-title
            >
            <v-col cols="12" sm="12" md="12">
              <base-select label="Unidad organizativa:" />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select label="Año:" />
            </v-col>
            <v-col cols="12" sm="12" md="12">
              <base-select label="Mes:" />
            </v-col>
          </v-card-text>
          <v-col align="start">
            <v-btn class="btn-normal-close" rounded> Generar reporte </v-btn>
          </v-col>
        </v-card>
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>
<script>
export default {
  data: () => ({
    // reports: [
    //   { class: "" },
    //   { class: "", dark: true },
    //   { class: "primary", dark: true },
    // ],

    reportTitle: "Generar Reportes",
  }),

  computed: {},
};
</script>

<style>
</style>