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
          <v-toolbar-title>Estrategias</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="600px" persistent>
            <template v-slot:activator="{}">
              <v-row>
                <v-col align="end">
                  <v-btn class="mb-2 btn-normal" @click="openModal" rounded>
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
                    <!-- Description Estrategica -->
                    <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Estrategia"
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
                        :rows="4"
                      />
                    </v-col>
                    <!-- Description Estrategica-->
                    <!-- Objetivo Programatico -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select-search
                        label="Objetivo Programático"
                        v-model.trim="$v.editedItem.description.$model"
                        :items="programmatic_objectives"
                        item="description"
                        :validation="$v.editedItem.description"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Objetivo Programatico -->
                    <!-- Unidad Organizativa -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select-search
                        label="Unidad Organizativa"
                        v-model.trim="$v.editedItem.ou_name.$model"
                        :items="organizational_units"
                        item="ou_name"
                        :validation="$v.editedItem.ou_name"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Unidad Organizativa -->
                    <!-- User -->
                    <v-col cols="12" sm="6" md="6" class="mt-2">
                      <base-select
                        label="Usuario"
                        v-model.trim="$v.editedItem.user_name.$model"
                        :items="users"
                        item="user_name"
                        :validation="$v.editedItem.user_name"
                      />
                    </v-col>
                    <!-- User -->
                    <!-- Executed -->
                    <v-col cols="12" sm="6" md="6" class="pt-2">
                      <v-checkbox
                        v-model="$v.editedItem.executed.$model"
                        label="Ejecutado"
                      ></v-checkbox>
                    </v-col>
                    <!-- Executed-->
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
import userApi from "../apis/userApi";
import organizationalUnitApi from "../apis/organizationalUnitApi";
import programmaticObjectiveApi from "../apis/programmaticObjectiveApi";
import strategyCuscaApi from "../apis/strategyCuscaApi";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "ESTRATEGÍA", value: "description_strategy" },
      { text: "OBJETIVO PROGRAMATICO", value: "description" },
      { text: "UNIDAD ORGANIZATIVA", value: "ou_name" },
      { text: "EJECUTADO", value: "executed" },
      { text: "USUARIO", value: "user_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      user_name: "",
      ou_name: "",
      description: "",
      description_strategy: "",
      executed: "",
    },
    defaultItem: {
      user_name: "",
      ou_name: "",
      description: "",
      description_strategy: "",
      executed: "",
    },
    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    users: [],
    organizational_units: [],
    programmatic_objectives: [],
    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      user_name: {
        required,
      },
      ou_name: {
        required,
        minLength: minLength(1),
      },
      description: {
        required,
        minLength: minLength(1),
      },
      description_strategy: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      executed: {
        required,
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
        userApi.get(null, {
          params: { skip: 0, take: 200 },
        }),
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
        this.records = responses[1].data.strategy_cusca;
        this.users = responses[0].data.users;
        this.programmatic_objectives =
          responses[2].data.programmatic_objectives;
        this.organizational_units = responses[3].data.organizationalUnits;
        //console.log(responses);
        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.user_name.$model = this.editedItem.user_name;
      this.$v.editedItem.ou_name.$model = this.editedItem.ou_name;
      this.$v.editedItem.description.$model = this.editedItem.description;
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

    openModal() {
      this.dialog = true;
      this.editedItem.user_name = this.users[0].user_name;
      this.editedItem.ou_name = this.organizational_units[0].ou_name;
      this.editedItem.description = this.programmatic_objectives[0].description;
      this.editedItem.description_strategy = "";
      this.editedItem.executed = false;
    },
  },
};
</script>
