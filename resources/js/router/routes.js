// GeneralViews
// Admin pages
import Login from "@/pages/Login/Login.vue";
import Otp from "@modules/Auth/resources/js/Pages/Otp/Otp.vue";
import Signup from "@/pages/Signup/Signup.vue";
import PrimaryDocuments from "@modules/UserKyc/resources/js/Pages/PrimaryDocuments/PrimaryDocuments.vue"
import Success from "@/pages/Success/Success.vue";
import Steps from "@modules/Auth/resources/js/Pages/Steps/steps.vue";
import ForgotPassword from "@modules/Auth/resources/js/Pages/ForgotPassword/ForgotPassword.vue";
import ChoosePassword from "@modules/Auth/resources/js/Pages/ForgotPassword/ChoosePassword.vue";
import UserProfile from "@modules/Profile/resources/js/Pages/UserProfile/UserProfile.vue"
import InsuranceQuestion from "@/pages/InsuranceQuestion/InsuranceQuestion.vue"
import WelcomeInvite from "@/pages/Welcome/WelcomeInvite.vue";
import InvitationMethod from "@/pages/InvitationMethod/InvitationMethod.vue"
import PaymentMethods from "@modules/Wallet/resources/js/Pages/PaymentMethods/PaymentMethods.vue";
import DocType from "@modules/UserKyc/resources/js/Pages/DocType/DocType.vue";
import SecondaryDocument from "@modules/UserKyc/resources/js/Pages/SecondaryDocuments/SecondaryDocuments.vue"
import OtherDocument from "@modules/UserKyc/resources/js/Pages/OtherDocuments/OtherDocuments.vue"
import BillerDetails from "@/pages/BillerDetails/BillerDetails.vue";
import InviteFriendsAndFamily from "@/pages/InviteFriendsAndFamily/InviteFriendsAndFamily.vue";
import AuthLayout from "../layout/authLayout.vue";
import MainDashboard from "@modules/Dashboard/resources/js/Pages/MainDashboard/MainDashboard.vue";
import TransactionHistory from "../../../Modules/Wallet/resources/js/Pages/TransactionHistory/TransactionHistory.vue";
import WelcomeZyanaza from "../pages/WelcomeZyanaza/WelcomeZyanaza.vue";
import ExpenseTracker from "@/pages/ExpenseTracker/ExpenseTracker.vue"
import ProfileSettings from "@modules/UserKyc/resources/js/Pages/ProfileSettings/ProfileSettings.vue";
import InvestmentDashboard from "@/pages/InvestmentDashboard/InvestmentDashboard.vue"
import Contribution from "@/pages/Contribution/Contribution.vue";
import DepositeFunds from "@modules/Wallet/resources/js/Pages/DepositeFunds/DepositeFunds.vue";
import SetupDividendDistribution from "@/pages/SetupDividendDistribution/SetupDividendDistribution.vue";
import WalletSummary from "@/pages/WalletSummary/WalletSummary.vue";
import FundPlan from "@/pages/FundPlan/FundPlan.vue";
import HospitalBillPayment from "@/pages/HospitalBillPayment/HospitalBillPayment.vue";
import BankAccountDetails from "@/pages/BankAccountDetails/BankAccountDetails.vue";
import BpayAccountDetails from "@/pages/BpayAccountDetails/BpayAccountDetails.vue";
import AddFundTransfer from "@/pages/AddFundTransfer/AddFundTransfer.vue";
import AddBills from "@/pages/AddBills/AddBills.vue";
import BpaySummary from "@/pages/BpaySummary/BpaySummary.vue";
import PaymentBillSummary from "@/pages/PaymentBillSummary/PaymentBillSummary.vue";
import ContributionPlan from "@/pages/ContributionPlan/ContributionPlan.vue";
import RegularPayment from "@/pages/RegularPayment/RegularPayment.vue";
import PaymentSummary from "@/pages/PaymentSummary/PaymentSummary.vue"
import ActivateContribution from "@/pages/ActivateContribution/ActivateContribution.vue";
import TransferAmountCr from "@/pages/TransferAmountCr/TransferAmountCr.vue";
import TotalPayable from "@/pages/PaymentBillSummary/TotalPayable.vue";
import ContributionDetails from "@/pages/ContributionDetails/ContributionDetails.vue";
import AddContributors from "@/pages/AddContributors/AddContributors.vue";
import WithdrawFunds from "@/pages/WithdrawFunds/WithdrawFunds.vue";
import WithdrawFundsWallet from "@/pages/WithdrawFundsWallet/WithdrawFundsWallet.vue";
import AddFundsCard from "@/pages/AddFundsCard/AddFundsCard.vue";
import AddBillsManually from "@/pages/AddBillsManually/AddBillsManually.vue";
import PayamentSummaryRoyal from "@/pages/PaymentBillSummary/PayamentSummaryRoyal.vue";

const routes = [
  {
    path: "/",
    // component: DashboardLayout,
    redirect: "/login",
    children: [
      {
        path: "login",
        name: "login",
        component: Login,
      },
      {
        path: "signup",
        name: "signup",
        component: Signup,
      },
      {
        path: "forgotpassword",
        name: "forgotpassword",
        component: ForgotPassword,
      },
      {
        path: "otp",
        name: "otp",
        component: Otp,
      },
      {
        path: "primary-documents",
        name: "primarydocuments",
          component: PrimaryDocuments,
      },
      {
        path: "success",
        name: "success",
        component: Success,
      },
      {
        path: "steps",
        name: "steps",
        component: Steps,
      },
      {
        path: "reset-password",
        name: "choose",
        component: ChoosePassword,
      },
      {
        path: "doc-type",
        name: "doctype",
        component: DocType,
      },
      {
        path: "profile",
        name: "profile",
        component: UserProfile,
      },
      {
        path: "insurance",
        name: "insurance",
        component: InsuranceQuestion,
      },
      {
        path: "invitation-method",
        name: "invitation-method",
        component: InvitationMethod,
      },
      {
        path: "welcome-invite",
        name: "welcome-invite",
        component: WelcomeInvite,
      },
      {
        path: "payment-method",
        name: "payment-method",
        component: PaymentMethods,
      },
      {
        path: "secondary-documents",
        name: "secondary-documents",
        component: SecondaryDocument,
      },
      {
        path: "other-documents",
        name: "other-documents",
        component: OtherDocument,
      },
      {
        path: "biller-details",
        name: "biller-details",
        component: BillerDetails,
      },
      {
        path: "invite-friends-family",
        name: "invite-friends-family",
        component: InviteFriendsAndFamily,
      },
      {
        path: "welcome-zyanaza",
        name: "welcome-zyanaza",
        component: WelcomeZyanaza,
      },
      {
        path: "contribution-plan",
        name: "contribution-plan",
        component: ContributionPlan,
      },
      {
        path: "regular-payment",
        name: "regular-payment",
        component: RegularPayment,
      },
      {
        path: "payment-summary",
        name: "payment-summary",
        component: PaymentSummary,
      },
      {
        path: "activate-contribution",
        name: "activate-contribution",
        component: ActivateContribution,
      },
      {
        path: "transfer-amount-cr",
        name: "transfer-amount-cr",
        component: TransferAmountCr,
      },
    ],
  },
  {
    path: "/",
    component: AuthLayout,
    redirect: "/dashboard",
    children: [
      {
        path: "dashboard",
        name: "dashboard",
        component: MainDashboard,
      },
      {
        path: "expense-tracker",
        name: "expense-tracker",
        component: ExpenseTracker,
      },
      {
        path: "transaction-history",
        name: "transaction-history",
        component: TransactionHistory,
      },

      {
        path: "profile-settings",
        name: "profile-settings",
        component: ProfileSettings,
      },
      {
        path: "investment-dashboard",
        name: "investment-dashboard",
        component: InvestmentDashboard,
      },
      {
        path: "contribution",
        name: "contribution",
        component: Contribution,
      },
      {
        path: "deposite-funds",
        name: "deposite-funds",
        component: DepositeFunds,
      },
      {
        path: "setup-dividend",
        name: "setup-dividend",
        component: SetupDividendDistribution,
      },
      {
        path: "wallet-summary",
        name: "wallet-summary",
        component: WalletSummary,
      },
      {
        path: "fund-plan",
        name: "fund-plan",
        component: FundPlan,
      },
      {
        path: "hospital-bill",
        name: "hospital-bill",
        component: HospitalBillPayment,
      },
      {
        path: "bank-account-details",
        name: "bank-account-details",
        component: BankAccountDetails,
      },
      {
        path: "bpay-account-details",
        name: "bpay-account-details",
        component: BpayAccountDetails,
      },
      {
        path: "bpay-summary",
        name: "bpay-summary",
        component: BpaySummary,
      },
      {
        path: "add-funds-transfer",
        name: "add-funds-transfer",
        component: AddFundTransfer,
      },
      {
        path: "add-bills",
        name: "add-bills",
        component: AddBills,
      },
      {
        path: "payment-bill-summary",
        name: "payment-bill-summary",
        component: PaymentBillSummary,
      },
      {
        path: "total-payable",
        name: "total-payable",
        component: TotalPayable,
      },
      {
        path: "contribution-details",
        name: "contribution-details",
        component: ContributionDetails,
      },
      {
        path: "add-contributors",
        name: "add-contributors",
        component: AddContributors,
      },
      {
        path: "withdraw-funds",
        name: "withdraw-funds",
        component: WithdrawFunds,
      },
      {
        path: "withdraw-funds-wallet",
        name: "withdraw-funds-wallet",
        component: WithdrawFundsWallet,
      },
      {
        path: "add-funds-card",
        name: "add-funds-card",
        component: AddFundsCard,
      },
      {
        path: "add-bill-manually",
        name: "add-bill-manually",
        component: AddBillsManually,
      },
      {
        path: "royal-summary",
        name: "royal-summary",
        component: PayamentSummaryRoyal,
      },
    ],
  },
];

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes;
