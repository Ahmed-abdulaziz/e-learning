<template>
  <main class="tw-antialiased tw-font-sans" data-version='{version}'>
    <slot name="header" />
    <section class="tw-p-4">
      <div v-if="steps && (!messages.loading || !registration.form.success)" class="tw-px-4 tw-pb-6">
        <div class="tw-relative tw-flex tw-items-center tw-justify-between tw-w-10/12 tw-mx-auto steps tw-z-20">
          <span
            v-for="(step, index) in registration.schema.schema.steps"
            :key="index"
            :style="stepBackground(index)"
            class="tw-flex tw-items-center tw-justify-center tw-block tw-font-bold tw-text-white tw-rounded-full tw-step tw-h-7 tw-w-7"
          >{{ index + 1 }}</span>
        </div>
      </div>

      <registration-form-alert :content="successMessage" :visible="registration.form.success" :has-content="!!$slots['success']" type="success">
        <slot name="success" />
      </registration-form-alert>

      <registration-form-alert :content="unexpectedMessage" :visible="messages.unexpected" :has-content="!!$slots['unexpected']" type="error">
        <slot name="unexpected" />
      </registration-form-alert>

      <registration-form-alert :content="loadingMessage" :visible="messages.loading" :has-content="!!$slots['loading']">
        <slot name="loading" />
      </registration-form-alert>

      <form v-if="!registration.form.success" v-show="!(steps && messages.loading)" @submit.prevent="onRegistration">
        <template v-for="(property, field) in properties">
          <template v-if="shouldShowField(field, property)">
            <registration-form-input-handler
              v-model="registration.schema.formData[field]"
              :key="field"
              :field="field"
              :property="property"
              :registration="registration"
              :steps="steps"
              :underage-redirect-link="underageRedirectLink"
              @keypress="inputRules($event, field)"
              @keyup="registration.schema.cacheFormFieldByKey(field)"
              @input="onInput($event, field)"
            />
          </template>
        </template>
        <registration-form-button
          v-if="showFormButtonComponent"
          :content="buttonText"
          :stepContent="stepButtonText"
          :loading="loading"
          :has-content="!!$slots['button']"
          :show-step-button="showStepButton"
        >
          <slot :name="formButtonName" :loading="loading" />
        </registration-form-button>
      </form>
    </section>
    <slot name="footer" />
  </main>
</template> 

<script>
import HandleTranslations from '../mixins/HandleTranslations.js'
import Registration from 'registration'
import RegistrationFormAlert from './registration-form-alert.vue'
import RegistrationFormButton from './registration-form-button.vue'
import RegistrationFormInputHandler from './registration-form-input-handler.vue'
export default {
  name: 'RegistrationForm',
  components: { RegistrationFormInputHandler, RegistrationFormAlert, RegistrationFormButton },
  mixins: [HandleTranslations],
  props: {
    environment: {
      type: String,
      default: 'staging'
    },
    url: {
      type: String,
      default: window.location.origin,
    },
    componentType: {
      type: String,
      default: 'registration-form'
    },
    registerEndpoint: {
      type: String,
      default: '/services/offers/register',
    },
    schemaEndpoint: {
      type: String,
      default: '/services/offers/register',
    },
    offer: {
      type: String,
      required: true
    },
    fake: {
      type: Boolean,
      default: false,
    },
    countryCode: {
      type: String,
      default: 'GB'
    },
    language: {
      type: String,
      default: 'en'
    },
    block: {
      type: String,
      default: 'hero'
    },
    clickId: {
      type: [String, null],
      default: new URLSearchParams(window.location.search).get('clickId')
    },
    clientDate: {
      type: [String, null],
      default: new Date().toString()
    },
    pvid: {
      type: [String, null],
      default: new URLSearchParams(window.location.search).get('pvid')
    },
    successMessage: {
      type: String,
      default: 'Thanks for registering, we’ll be in touch.',
    },
    unexpectedMessage: {
      type: String,
      default: 'Unexpected error, please try again',
    },
    loadingMessage: {
      type: String,
      default: 'Your registration is still in progress',
    },
    buttonText: {
      type: String,
      default: 'Register Now',
    },
    stepButtonText: {
      type: String,
      default: 'Next',
    },
    sendToPartner: {
      type: Boolean,
      default: true
    },
    includesConsent: {
      type: Boolean,
      default: false
    },
    includesMinimumAge: {
      type: Boolean,
      default: false
    },
    verificationUri: {
      type: [String, null],
      default: null
    },
    steps: {
      type: Boolean,
      default: false
    },
    /**
     * Default link to use if the user is not over minumum age
     */
    underageRedirectLink: {
      type: [String, null],
    },
    getOfferById: {
      type: Boolean,
      default: false
    },
  },
  data () {
    return {
      loading: false,
      loadingTimeout: null,
      messages: {
        unexpected: false,
        loading: false,
      },
      registration: new Registration(this.environment, {
        uri: this.url,
        sendToPartner: this.sendToPartner,
        includesConsent: this.includesConsent,
        includesMinimumAge: this.includesMinimumAge,
        verificationUri: this.verificationUri,
        isFake: this.fake,
        getOfferById: this.getOfferById,
        endpoints: {
          schema: this.schemaEndpoint,
          registration: this.registerEndpoint,
        },
        formData: {
          offer: this.offer,
          countryCode: this.countryCode,
          language: this.language,
          clickId: this.clickId,
          clientDate: this.clientDate,
          pvid: this.pvid,
          block: this.block,
        },
      }),
    }
  },
  computed: {
    properties() {
      return this.registration.schema.schema.properties
    },
    currentStepProperties() {
      return this.registration.schema.schema.steps[this.registration.schema.currentStep]
    },
    showStepButton() {
      if (!this.steps) return false
      return this.registration.schema.schema.steps.length - 1 > this.registration.schema.currentStep
    },
    formButtonName() {
      return this.showStepButton ? 'step-button' : 'button'
    },
    currentStepIsBoolean() {
      return Object.entries(this.properties).find(([propertyName, { type }]) =>
        this.currentStepProperties.includes(propertyName) && type === 'boolean'
      )
    },
    showFormButtonComponent() {
      if (!this.steps) return true
      return !this.currentStepIsBoolean
    },
  },
  created() {
    this.registration.schema.cacheFormFieldByKey('browserLanguage', this.getBrowserLanguage())
  },
  watch: {
    loading(isLoading) {
      if (this.steps && isLoading) {
        this.messages.loading = this.loading
      } else if (isLoading) {
        this.loadingTimeout = setTimeout(() => this.messages.loading = this.loading, 6000)  
      }
    },
  },
  methods: {
    getBrowserLanguage() {
      if (typeof navigator === 'undefined') {
        this.language = this.adgroupLanguage
      } else {
        const [language] = (navigator.language || navigator.userLanguage).split('-')
        this.language = language
      }
      return this.language
    },
    onInput() {
      if (this.steps && this.currentStepIsBoolean) this.onRegistration()
    },
    stepBackground(index) {
      const color = this.registration.schema.currentStep === index ? '#de4330' : '#5fa5fa'
      return `background: ${color}`
    },
    reset () {
      this.loading = false
      this.loadingTimeout = null
      this.messages.unexpected = false
      this.messages.loading = false
      clearTimeout(this.loadingTimeout)
    },
    inputRules(event, field) {
      if (field === "phone" && (!/\d/.test(event.key) && event.key !== " "))
        return event.preventDefault()
    },
    shouldShowField(key, field) {
      if (!field.meta) return false
      if (field.meta.inputType === 'hidden') return false
      if (!this.show_opt_in && key === 'optIn') return false
      if (typeof this.allowedFields !== 'undefined') {
        if (!this.allowedFields.includes(key)) return false
      }
      if (this.steps) return this.currentStepProperties.includes(key)
      return true
    },
    onFailureRegistration() {
      this.reset()
      if (!this.registration.form.error)
        this.messages.unexpected = true
    },
    onSuccessfulRegistration() {
      this.reset()
      const language = new URLSearchParams(window.location.search).get('language') || 'en'
      const verificationUri = this.verificationUri
      const redirectUrl = this.registration.form.getRedirectUrl()
      if (verificationUri) {
        const concat = `&redirect=${redirectUrl}`
        window.top.location.href = `https://${window.location.hostname}/verification?language=${language}${concat}` 
      }
      if (!verificationUri && redirectUrl) {
        window.top.location.href = redirectUrl
      }
    },
    onFakeRegistration() {
      this.reset()
      const fields = ['firstName','lastName','email','phone']
      fields.forEach(field => this.registration.schema.formData[field] = '')
      localStorage.removeItem('offer.' + this.registration.schema.formData.offer)
      this.registration.form.setSuccess(true)
      this.registration.form.setRedirectUrl(null)
    },
    onRegistration() {
      if (this.loading) return
      if (this.showStepButton) return this.registration.schema.incrementStep() 

      this.loading = true
      if (this.fake) return this.onFakeRegistration()
      this.registration.form.register()
        .then(this.onSuccessfulRegistration)
        .catch(this.onFailureRegistration)
    },
  }
}
</script>
<style>
  .steps:before {
    content: "";
    height: 2px;
    width: 100%;
    position: absolute;
    background: #cad0db;
    z-index: -1;
  }
</style>