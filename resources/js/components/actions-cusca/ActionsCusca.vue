<template>
  <div data-app>
    <alert-time-out
      :redirect="redirectSessionFinished"
      @redirect="updateTimeOut($event)"
    />
    <alert
      :text="textAlert"
      :event="alertEvent"
      :show="showAlert"
      @show-alert="updateAlert($event)"
      class="mb-2"
    />
    <v-data-table
      :headers="headers"
      :items="recordsFiltered"
      sort-by="action_description"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Acciones</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px" persistent>
            <template v-slot:activator="{}">
              <v-row>
                <v-col align="end">
                  <v-btn class="mb-2 btn-normal" rounded @click="openModal">
                    Agregar
                  </v-btn>
                </v-col>
                <v-col
                  xs="6"
                  sm="6"
                  md="6"
                  class="d-none d-md-block d-lg-block"
                >
                  <v-text-field
                    dense
                    label="Buscar"
                    outlined
                    type="text"
                    class=""
                    v-model="search"
                    @keyup="searchValue()"
                  ></v-text-field>
                </v-col>
              </v-row>
            </template>
            <v-card class="flexcard" height="100%">
              <v-card-title>
                <h1 class="mx-auto pt-3 mb-3 text-center black-secondary">
                  {{ formTitle }}
                </h1>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <!-- Form -->
                  <v-row>
                    <!-- Description Acciones -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Acciones"
                        v-model.trim="$v.editedItem.action_description.$model"
                        :validation="$v.editedItem.action_description"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                        :min="1"
                        :max="500"
                        :rows="2"
                      />
                    </v-col>
                    <!-- Description Acciones-->
                    <!-- Annual Actions -->
                    <v-col cols="12" sm="6" md="6">
                      <base-input
                        label="Número de acciones"
                        v-model.trim="$v.editedItem.annual_actions.$model"
                        :validation="$v.editedItem.annual_actions"
                        v-mask="'####'"
                        type="number"
                        :validationsInput="{
                          required: true,
                        }"
                        :min="2000"
                        :max="2050"
                      />
                    </v-col>
                    <!-- Annual Actions -->
                    <!-- Executed -->
                    <v-col cols="12" sm="6" md="6" class="pt-0">
                      <v-checkbox
                        v-model="$v.editedItem.executed.$model"
                        label="Ejecutado"
                      ></v-checkbox>
                    </v-col>
                    <!-- Executed-->
                    <!-- Responsable -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Responsable"
                        v-model="$v.editedItem.responsable_name.$model"
                        :validation="$v.editedItem.responsable_name"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Responsable -->
                     <!--Verification Method -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Método de Verificación"
                        v-model="$v.editedItem.verification_method.$model"
                        :validation="$v.editedItem.verification_method"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Verification Method -->
                    <!-- Data Source -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Fuente de datos"
                        v-model="$v.editedItem.data_source.$model"
                        :validation="$v.editedItem.data_source"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Data Source -->
                    <!--Mesure Unit -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Método de Verificación"
                        v-model="$v.editedItem.mesure_unit.$model"
                        :validation="$v.editedItem.mesure_unit"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Mesure Unit -->
                     <!-- Bubget executed -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Presupuesto ejecutado"
                        v-model.trim="$v.editedItem.budget_executed.$model"
                        :validation="$v.editedItem.budget_executed"
                        type="number"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Bubget executed -->
                    <!-- Users -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Usuario"
                        v-model.trim="$v.editedItem.user_name.$model"
                        :items="users"
                        item="user_name"
                        :validation="$v.editedItem.user_name"
                      />
                        <!-- :readonly="true" -->
                    </v-col>
                    <!-- Users -->
                    <!-- Results -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Resultado"
                        v-model="$v.editedItem.result_description.$model"
                        :items="resultsCusca"
                        item="result_description"
                        :validation="$v.editedItem.result_description"
                      />
                    </v-col>
                    <!-- Results -->
                    <!-- Months
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Mes"
                        v-model.trim="$v.editedItem.month_name.$model"
                        :items="months"
                        item="month_name"
                        :validation="$v.editedItem.month_name"
                      />
                    </v-col>
                    Months -->
                    <!-- Years
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Año"
                        v-model.trim="$v.editedItem.year_name.$model"
                        :items="years"
                        item="year_name"
                        :validation="$v.editedItem.year_name"
                      />
                    </v-col>
                    Years -->
                    
                  </v-row>
                  <!-- Form -->
                  <v-row>
                    <v-col align="center">
                      <v-btn
                        color="btn-normal no-uppercase mt-3"
                        rounded
                        @click="save"
                      >
                        Guardar
                      </v-btn>

                      <v-btn
                        color="btn-normal-close no-uppercase mt-3"
                        rounded
                        @click="close"
                      >
                        Cancelar
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="400px">
            <v-card class="h-100">
              <v-container>
                <h3 class="black-secondary text-center mt-3 mb-3">
                  Eliminar registro
                </h3>
                <v-row>
                  <v-col align="center">
                    <v-btn
                      color="btn-normal no-uppercase mt-3 mb-3 pr-5 pl-5 mx-auto"
                      rounded
                      @click="deleteItemConfirm"
                      >Confirmar</v-btn
                    >
                    <v-btn
                      color="btn-normal-close no-uppercase mt-3 mb-3"
                      rounded
                      @click="closeDelete"
                    >
                      Cancelar
                    </v-btn>
                  </v-col>
                </v-row>
              </v-container>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
      </template>
      <template v-slot:no-data>
        <a
          href="#"
          class="btn btn-normal-secondary no-decoration"
          @click="initialize"
        >
          Reiniciar
        </a>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import Validations from "./Validations"
import Methods from "./methods"
// import moment from "moment";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "ACCIÓN", value: "action_description" },
      { text: "NO. DE ACCIONES ANUALES", value: "annual_actions"},
      { text: "EJECUTADO", value: "executed" },
      { text: "RESPONSABLE", value: "responsable_name" },
      { text: "METODO DE VERIFICACIÓN", value: "verification_method" },
      { text: "FUENTE DE DATOS", value: "data_source" },
      { text: "UNIDAD DE MEDIDA", value: "mesure_unit" },
      { text: "PRESUPUESTO EJECUTADO", value: "budget_executed" },
      { text: "USUARIO", value: "user_name" },
      { text: "RESULTADO", value: "result_description" },
      //{ text: "MES", value: "month_name" },
      //{ text: "AÑO", value: "year_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      action_description: "",
      annual_actions: 0,
      responsable_name: "",
      verification_method:"",
      data_source:"",
      mesure_unit:"",
      budget_executed:0,
      user_name: "",
      result_description: "",
      //month_name: "",
      //year_name: "",
      executed: false,
    },
    defaultItem: {
      action_description: "",
      annual_actions: 0,
      responsable_name: "",
      verification_method:"",
      data_source:"",
      mesure_unit:"",
      budget_executed:0,
      user_name: "",
      result_description: "",
      //month_name: "",
      //year_name: "",
      executed: false,
    },

    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    users: [],
    resultsCusca: [],
    //months: [],
    //years: [],
    redirectSessionFinished: false,
    actualUser: {},
  }),

  // Validations
  validations: Validations,
  // Validations
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nuevo registro" : "Editar registro";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: Methods
};
</script>
