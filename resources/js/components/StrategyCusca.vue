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
      sort-by="description_strategy"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Estrategías</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px" persistent>
            <template v-slot:activator="{ on, attrs }">
              <v-row>
                <v-col align="end">
                  <v-btn
                    class="mb-2 btn-normal"
                    v-bind="attrs"
                    v-on="on"
                    rounded
                  >
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
                    <!-- Unidad Organizativa -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Unidad Organizativa"
                        v-model.trim="$v.editedItem.ou_name.$model"
                        :items="organizational_units"
                        item="ou_name"
                        :validation="$v.editedItem.ou_name"
                      />
                    </v-col>
                    <!-- Unidad Organizativa -->
                    <!-- Objetivo Programatico -->    
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Objetivo Programático"
                        v-model.trim="$v.editedItem.description.$model"
                        :items="programmatic_objectives"
                        item="description"
                        :validation="$v.editedItem.description"
                      />
                    </v-col>
                    <!-- Objetivo Programatico -->      
                    <!-- Description Estrategica -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Descripción"
                        v-model.trim="$v.editedItem.description_strategy.$model"
                        :validation="$v.editedItem.description_strategy"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                        :min="1"
                        :max="500"
                        :rows="3"
                      />
                    </v-col>
                    <!-- Description Estrategica-->
                     <!-- Date -->
                    <v-col cols="12" xs="12" sm="12" md="6">
                      <base-input
                        label="Fecha de creación"
                        v-model.trim="$v.editedItem.create_date.$model"
                        :validation="$v.editedItem.create_date"
                        type="date"
                        :validationsInput="{
                          required:true,
                        }"
                      />
                    </v-col>
                    <!-- Date -->
                    <!-- Responsable -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Responsable"
                        v-model="$v.editedItem.user_create_strategy.$model"
                        :validation="$v.editedItem.user_create_strategy"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Responsable -->
                    <!-- Percentage -->
                    <v-col cols="12" sm="12" md="6">
                      <base-input
                        label="Porcentaje"
                        v-model.trim="$v.editedItem.percentage.$model"
                        :validation="$v.editedItem.percentage"
                        type="number"
                        :validationsInput="{
                          required: true,
                        }"
                      />
                    </v-col>
                    <!-- Percentage -->
                   
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

import organizationalUnitApi from "../apis/organizationalUnitApi";
import programmaticObjectiveApi from "../apis/programmaticObjectiveApi";
import strategyCuscaApi from "../apis/strategyCuscaApi";
import lib from "../libs/function";
import { required, minLength, maxLength, helpers, } from "vuelidate/lib/validators";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "UNIDAD ORGANIZATIVA", value: "ou_name" },
      { text: "OBJETIVO PROGRAMATICO", value: "description" },
      { text: "DESCRIPCIÓN", value: "description_strategy" },
      { text: "FECHA DE CREACIÓN", value: "create_date" },
      { text: "RESPONSABLE", value: "user_create_strategy" },
      { text: "%", value: "percentage" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      user_create_strategy: "",
      ou_name: "",
      description: "",
      description_strategy: "",
      percentage: "",
      create_date:"",
    },
    defaultItem: {
      user_create_strategy: "",
      ou_name: "",
      description: "",
      description_strategy: "",
      percentage: "",
      create_date:"",
    },
    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    organizational_units: [],
    programmatic_objectives: [],
    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      user_create_strategy: {
        minLength: minLength(1),
        maxLength: maxLength(250),
        required,
      },
      ou_name: {
        required,
      },
      description: {
        required,
      },
      description_strategy: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      percentage: {
        required,
      },
      create_date: {
        required,
        isValidBirthday: helpers.regex(
          "isValidBirthday",
          /([0-9]{4}-[0-9]{2}-[0-9]{2})/
        ),
        minDate: (value) => value > new Date("1920-01-01").toISOString(),
        maxDate: () => {
          let today = new Date();
          let year = today.getFullYear() - 18;
          let date = today.setFullYear(year);
          return new Date(date).toISOString();
        },
      },
    },
  },
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

  methods: {
    async initialize() {
      this.records = [];
      this.recordsFiltered = [];

      let requests = [
        strategyCuscaApi.get(),
        programmaticObjectiveApi.get(),
        organizationalUnitApi.get(),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.strategyCusca;
        this.programmatic_objectives = responses[1].data.programmatic_objectives;
        this.organizational_units = responses[2].data.organizational_units;
        //this.editedItem.user_name = this.users[1].user_name;
        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.ou_name.$model = this.editedItem.ou_name;
      this.$v.editedItem.description.$model =this.editedItem.description;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await strategyCuscaApi
        .delete(`/${this.editedItem.id}`)
        .catch((error) => {
          this.updateAlert(
            true,
            "No fue posible eliminar el registros.",
            "fail"
          );
          this.close();
        });

      if (res.data.message == "success") {
        this.redirectSessionFinished = lib.verifySessionFinished(
          res.status,
          200
        );
        this.updateAlert(true, "Registro eliminado.", "success");
      } else {
        this.updateAlert(true, "No se pudo eliminar el registro.", "fail");
      }

      this.initialize();
      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1; 
      });
    },

    closeDelete() {
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
      });

      this.dialogDelete = false;
    },

    async save() {
      this.$v.$touch();
      if (this.$v.$invalid || this.editedItem.description == "") {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      if (this.editedIndex > -1) {
        const res = await strategyCuscaApi
          .put(`/${this.editedItem.id}`, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");
            this.close();
            this.redirectSessionFinished = lib.verifySessionFinished(
              error.response.status,
              419
            );
          });

        if (res.data.message == "success") {
          this.updateAlert(
            true,
            "Registro actualizado correctamente.",
            "success"
          );
        }
      } else {
        const res = await strategyCuscaApi
          .post(null, this.editedItem)
          .catch((error) => {
            this.updateAlert(true, "No fue posible crear el registro.", "fail");
            this.close();
          });

        if (res.data.message == "success") {
          this.updateAlert(
            true,
            "Registro almacenado correctamente.",
            "success"
          );
        }
      }
      this.close();
      this.initialize();
    },

    searchValue() {
      this.recordsFiltered = [];

      if (this.search != "") {
        this.records.forEach((record) => {
          let searchConcat = "";
          for (let i = 0; i < record.description_strategy.length; i++) {
            searchConcat += record.description_strategy[i].toUpperCase();
            if (
              searchConcat === this.search.toUpperCase() &&
              !this.recordsFiltered.some((rec) => rec == record)
            ) {
              this.recordsFiltered.push(record);
            }
          }
        });
        return;
      }

      this.recordsFiltered = this.records;
    },

    updateAlert(show = false, text = "Alerta", event = "success") {
      this.textAlert = text;
      this.alertEvent = event;
      this.showAlert = show;
    },

    updateTimeOut(event) {
      this.redirectSessionFinished = event;
    },
  },
};
</script>