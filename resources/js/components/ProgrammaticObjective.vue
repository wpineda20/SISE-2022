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
      sort-by="description"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Objetivos Programáticos</v-toolbar-title>
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
                  <!-- Description -->
                  <v-row v-if="users.length > 0">
                  <v-col cols="12" sm="6" md="12">
                      <base-text-area
                        label="Descripción"
                        v-model.trim="$v.editedItem.description.$model"
                        :validation="$v.editedItem.description"
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
                    <!-- Description -->
                    <!-- Estrategy_objective -->
                    <v-col cols="12" sm="6" md="12" class="pt-0">
                      <v-checkbox
                        v-model="$v.editedItem.strategy_objective.$model"
                        label="Objetivo de Estrategía"
                      ></v-checkbox>
                    </v-col>
                    <!-- Estrategy_objective -->
                    <!-- Create Date -->
                    <!--<v-col cols="12" sm="6" md="6">
                        <base-date-input
                            label="Fecha de creación"
                            v-model.trim="$v.editedItem.create_date.$model"
                            :items="programmatic_objectives"
                            item="create_date"
                        />
                    </v-col>-->
                    <!-- Create Date -->
                    <!-- Percentage -->
                    <v-col cols="12" sm="12" md="4">
                      <base-input
                        label="Porcentaje"
                        v-model="$v.editedItem.percentage.$model"
                        :validation="$v.editedItem.percentage"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Percentage -->
                    <!-- Institution Name -->
                   <v-col cols="12" sm="6" md="12">
                      <base-select
                        label="Institución"
                        v-model.trim="$v.editedItem.institution_name.$model"
                        :items="institutions"
                        item="institution_name"
                        :validation="$v.editedItem.institution_name"
                      />
                    </v-col>
                    <!-- Institution Name -->
                     <!-- User Name -->
                   <v-col cols="12" sm="6" md="12">
                      <base-select
                        label="Usuario"
                        v-model.trim="$v.editedItem.user_name.$model"
                        :items="users"
                        item="user_name"
                        :validation="$v.editedItem.user_name"
                      />
                    </v-col>
                    <!-- User Name -->

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
import institutionApi from "../apis/institutionApi";
import userApi from "../apis/userApi";
import programmaticObjectiveApi from "../apis/programmaticObjectiveApi";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "DESCRIPCION", value: "description" },
      { text: "OBJETIVO ESTRATEGICO", value: "strategy_objective" },
      { text: "FECHA DE CREACION", value: "create_date" },
      { text: "PORCENTAJE", value: "percentage" },
      { text: "INSTITUCION", value: "institution_name" },
      { text: "USUARIO", value: "user_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      description: "",
      strategy_objective: "",
      create_date:"",
      percentage:"",
      institution_name: "Ministerio de Cultura",
      user_name: "GiovanniTzec",
    },
    defaultItem: {
     description: "",
      strategy_objective: "",
      create_date:"",
      percentage:"",
      institution_name: "Ministerio de Cultura",
      user_name: "GiovanniTzec",
    },
    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    institutions: [],
    users: [],
    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      description: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
     strategy_objective: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      /*create_date: {
        required,
      },*/
       percentage: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(4),
      },
      institution_name: {
        required,
      },
      user_name: {
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

      let requests = [programmaticObjectiveApi.get(), institutionApi.get(), userApi.get()];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.programmatic_objectives;
        this.institutions = responses[1].data.institutions;
        this.users = responses[2].data.users;
        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.institution_name.$model = this.editedItem.institution_name;
      this.$v.editedItem.user_name.$model = this.editedItem.user_name;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await programmaticObjectiveApi
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
        this.editedItem.user_name = this.users[0].user_name;
      });
    },

    closeDelete() {
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
        this.editedItem.user_name = this.users[0].user_name;
      });

      this.dialogDelete = false;
    },

    async save() {
      this.$v.$touch();
      if (this.$v.$invalid || this.editedItem.institution_name == "") {
        this.updateAlert(true, "Campos obligatorios.", "fail");
        return;
      }

      if (this.editedIndex > -1) {
        const res = await programmaticObjectiveApi
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
            "Registro almacenado correctamente.",
            "success"
          );
        }
      } else {
        const res = await programmaticObjectiveApi
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
          for (let i = 0; i < record.description.length; i++) {
            searchConcat += record.description[i].toUpperCase();
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