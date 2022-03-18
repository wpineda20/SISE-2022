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
      sort-by="description_action"
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

                    <!-- Results -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Resultado"
                        v-model="$v.editedItem.result_description.$model"
                        :items="resultsCusca"
                        item="result_description"
                        :validation="$v.editedItem.result_description"
                        @change="changeSelect"
                      />
                    </v-col>
                    <!-- Results -->

                    <!-- Financing -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select-search
                        label="Financiamiento"
                        v-model.trim="$v.editedItem.financing_name.$model"
                        :items="financings"
                        item="financing_name"
                        :validation="$v.editedItem.financing_name"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Financing -->

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
                    <!-- Users -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Usuario"
                        v-model.trim="$v.editedItem.user_name.$model"
                        :items="users"
                        item="user_name"
                        :validation="$v.editedItem.user_name"
                        :readonly="true"
                      />
                    </v-col>
                    <!-- Users -->

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

                    <!-- Years -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Año"
                        v-model.trim="$v.editedItem.year_name.$model"
                        :items="years"
                        item="year_name"
                        :validation="$v.editedItem.year_name"
                      />
                    </v-col>
                    <!-- Years -->
                    <!-- Executed -->
                    <v-col cols="12" sm="6" md="6" class="pt-0">
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
import resultsCuscaApi from "../apis/resultsCuscaApi";
import yearApi from "../apis/yearApi";
import financingApi from "../apis/financingApi";
import actionsCuscaApi from "../apis/actionsCuscaApi";
import lib from "../libs/function";
import {
  required,
  minLength,
  maxLength,
  helpers,
} from "vuelidate/lib/validators";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "ACCIÓN", value: "action_description" },
      { text: "RESULTADO", value: "result_description" },
      { text: "FINANCIAMIENTO", value: "financing_name" },
      { text: "RESPONSABLE", value: "responsable_name" },
      { text: "NO. ACCIONES", value: "annual_actions" },
      { text: "EJECUTADO", value: "executed" },
      { text: "AÑO", value: "year_name" },
      { text: "USUARIO", value: "user_name" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      action_description: "",
      annual_actions: 0,
      responsable_name: "",
      user_name: "",
      result_description: "",
      year_name: "",
      financing_name: "",
      executed: false,
    },
    defaultItem: {
      action_description: "",
      annual_actions: 0,
      responsable_name: "",
      user_name: "",
      result_description: "",
      year_name: "",
      financing_name: "",
      executed: false,
    },

    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    users: [],
    resultsCusca: [],
    years: [],
    financings: [],
    redirectSessionFinished: false,
    actualUser: {},
  }),

  // Validations
  validations: {
    editedItem: {
      action_description: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(500),
      },
      responsable_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      user_name: {
        required,
      },
      result_description: {
        required,
        minLength: minLength(1),
      },
      year_name: {
        required,
        minLength: minLength(1),
      },
      executed: {
        required,
      },
      financing_name: {
        required,
        minLength: minLength(1),
      },
      annual_actions: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(10),
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
        actionsCuscaApi.get(),
        userApi.get(null, {
          params: { skip: 0, take: 200 },
        }),
        resultsCuscaApi.get(),
        yearApi.get(),
        financingApi.get(),
        userApi.post("/actualUser"),
      ];
      let responses = await Promise.all(requests).catch((error) => {
        this.updateAlert(true, "No fue posible obtener los registros.", "fail");
        this.redirectSessionFinished = lib.verifySessionFinished(
          error.response.status,
          401
        );
      });

      if (responses && responses[0].data.message == "success") {
        this.records = responses[0].data.actionsCusca;
        this.users = responses[1].data.users;
        this.resultsCusca = responses[2].data.resultsCusca;
        this.years = responses[3].data.years;
        this.financings = responses[4].data.financings;
        this.actualUser = responses[5].data.user;

        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      //   this.editedItem.user_name = item.user_name;
      //   this.editedItem.result_description = item.result_description;
      //   this.editedItem.value = item.value;
      //   this.editedItem.financing_name = item.financing_name;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await actionsCuscaApi
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
      if (this.$v.$invalid || this.editedItem.user_name == "") {
        this.updateAlert(true, "Campos obligatorios.", "fail");

        return;
      }

      if (this.editedIndex > -1) {
        const res = await actionsCuscaApi
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
        const res = await actionsCuscaApi
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
          for (let i = 0; i < record.description_action.length; i++) {
            searchConcat += record.description_action[i].toUpperCase();
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
      this.editedItem.result_description =
        this.resultsCusca[0].result_description;
      this.editedItem.user_name = this.actualUser.user_name;
      this.editedItem.value = new Date().getFullYear();
      this.editedItem.financing_name = this.financings[0].financing_name;
      this.editedItem.action_description = "";
      this.editedItem.responsable_name = "";
      this.editedItem.annual_actions = 0;

      this.editedItem.executed = false;
    },

    changeSelect() {
      console.log(this.resultsCusca, this.editedItem.result_description);
    },
  },
};
</script>
