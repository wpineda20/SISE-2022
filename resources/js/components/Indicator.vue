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
      sort-by="indicator_name"
      class="elevation-3 shadow p-3 mt-3"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Indicadores</v-toolbar-title>
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
                  <!-- Institution -->
                  <v-row v-if="institutions.length > 0">
                    <!-- Indicator Name -->
                    <v-col cols="12" sm="12" md="12">
                      <base-input
                        label="Indicador"
                        v-model="$v.editedItem.indicator_name.$model"
                        :validation="$v.editedItem.indicator_name"
                        validationTextType="default"
                        :validationsInput="{
                          required: true,
                          minLength: true,
                          maxLength: true,
                        }"
                      />
                    </v-col>
                    <!-- Indicator Name -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Institución"
                        v-model.trim="$v.editedItem.institution_name.$model"
                        :items="institutions"
                        item="institution_name"
                        :validation="$v.editedItem.institution_name"
                      />
                    </v-col>
                    <!-- Institution -->
                    <!-- Unit -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Unidad de medida"
                        v-model.trim="$v.editedItem.unit_name.$model"
                        :items="units"
                        item="unit_name"
                        :validation="$v.editedItem.unit_name"
                      />
                    </v-col>
                    <!-- Unit -->
                    <!-- Organizational Unit -->
                    <v-col cols="12" sm="6" md="6">
                      <base-select
                        label="Unidad organizativa"
                        v-model.trim="$v.editedItem.ou_name.$model"
                        :items="organizationalUnits"
                        item="ou_name"
                        :validation="$v.editedItem.ou_name"
                      />
                    </v-col>
                    <!-- Organizational Unit -->
                    <!-- Strategic Indicator -->
                    <v-col cols="12" sm="6" md="6" class="pt-0">
                      <v-checkbox
                        v-model="$v.editedItem.strategic_indicator.$model"
                        label="Activo"
                      ></v-checkbox>
                    </v-col>
                    <!-- Strategic Indicator -->
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
import unitApi from "../apis/unitApi";
import indicatorApi from "../apis/indicatorApi";
import organizationalUnitApi from "../apis/organizationalUnitApi";
import lib from "../libs/function";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  data: () => ({
    search: "",
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "NOMBRE", value: "indicator_name" },
      { text: "INSTITUCIÓN", value: "institution_name" },
      { text: "UNIDAD DE MEDIDA", value: "unit_name" },
      { text: "UNIDAD ORGANIZATIVA", value: "ou_name" },
      { text: "ACTIVO", value: "strategic_indicator" },
      { text: "ACCIONES", value: "actions", sortable: false },
    ],
    records: [],
    recordsFiltered: [],
    editedIndex: -1,
    editedItem: {
      strategic_indicator: "",
      indicator_name: "",
      institution_name: "Ministerio de Cultura",
      ou_name: "Archivo General de la Nación",
      unit_name: "Anual",
    },
    defaultItem: {
      strategic_indicator: "",
      indicator_name: "",
      institution_name: "Ministerio de Cultura",
      ou_name: "Archivo General de la Nación",
      unit_name: "Anual",
    },
    textAlert: "",
    alertEvent: "success",
    showAlert: false,
    institutions: [],
    units: [],
    organizationalUnits: [],
    redirectSessionFinished: false,
  }),

  // Validations
  validations: {
    editedItem: {
      strategic_indicator: {},
      indicator_name: {
        required,
        minLength: minLength(1),
        maxLength: maxLength(150),
      },
      institution_name: {
        required,
      },
      unit_name: {
        required,
      },
      ou_name: {
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
        indicatorApi.get(),
        institutionApi.get(),
        unitApi.get(),
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
        this.records = responses[0].data.indicators;
        this.institutions = responses[1].data.institutions;
        this.units = responses[2].data.units;
        this.organizationalUnits = responses[3].data.organizationalUnits;

        this.editedItem.institution_name =
          this.institutions[0].institution_name;
        this.recordsFiltered = this.records;
      }
    },

    editItem(item) {
      this.dialog = true;
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.$v.editedItem.institution_name.$model =
        this.editedItem.institution_name;
      this.$v.editedItem.unit_name.$model = this.editedItem.unit_name;
      this.$v.editedItem.ou_name.$model = this.editedItem.ou_name;
    },

    deleteItem(item) {
      this.editedIndex = this.recordsFiltered.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      const res = await indicatorApi
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
        this.editedItem.institution_name =
          this.institutions[0].institution_name;
      });
    },

    closeDelete() {
      this.$nextTick(() => {
        this.editedItem = this.defaultItem;
        this.editedIndex = -1;
        this.editedItem.institution_name =
          this.institutions[0].institution_name;
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
        const res = await indicatorApi
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
        const res = await indicatorApi
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
          for (let i = 0; i < record.indicator_name.length; i++) {
            searchConcat += record.indicator_name[i].toUpperCase();
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

