import FormGroupInput from "./Inputs/formGroupInput.vue";
import MainBanner from "./Banner/mainBanner.vue"
import ReuseableButton from "./Button/reuseableButton.vue";
import AppTypography from "./Typography/appTypography.vue"
import BackButton from "./Back/backButton.vue";
import CapsuleField from "./Capsule/capsuleField.vue"
import CommonDocuments from "./CommonDocuments/commonDocuments.vue"
import ProfileCard from "./ProfileCard/profileCard.vue"
import EditProfileCard from "./ProfileCard/editCard.vue"
import SecurityQuestions from "./ProfileCard/SecurityQuestions.vue"
import AccountDetails from "./ProfileCard/AccountDetails.vue"
import KYCDocuments from "./ProfileCard/KYCDocuments.vue"
import CommonFooter from "./CommonFooter/commonFooter.vue";
import InvitationMethod from "./InvitationMethod/invitationMethod.vue";
import LogoHeader from "./Header/logoHeader.vue";
import ImageSvg from "./ImageSvg/imageSvg.vue";
import InsuranceQuestion from "@modules/Insurance/resources/js/Pages/InsuranceQuestion/InsuranceQuestion.vue"
import PremiumDetails from "./InsurancePremiumDetails/premiumDetails.vue";
import SuccessAlert from "./SuccessAlert/successAlert.vue";
import WelcomeInvite from "./Welcome/welcomeInvite.vue";
import MainFooter from "./MainFooter/mainFooter.vue";
import PaymentMethod from "./PaymentMethods/paymentMethod.vue"
import ReusablePaymentMethod from "./PaymentMethods/ReusablePaymentMethod.vue"
import AppSelect from "./Select/appSelect.vue";
import DocType from "./DocType/docType.vue";
import BillerDetails from "./BillerDetails/billerDetails.vue";
import InviteFriendsAndFamily from "./InviteFriendsAndFamily/inviteFriendAndFamily.vue";
import TitleActionBar from "./TitleActionBar/titleActionBar.vue";
import DetailsCard from "./DetailsCard/detailsCard.vue";
import WalletHistory from "./WalletHistory/walletHistory.vue";
import ContributionListCard from "./ContributionListCard/contributionListCard.vue";
import Dropdown from "./Dropdown/dropdownMenu.vue";
import CommonTable from "./CommonTable/commonTable.vue";
import PaymentAnalyticsCard from "./PaymentAnalyticsCard/paymentAnalyticsCard.vue";
import ZyanazaCard from "./ZyanazaCard/zyanazaCard.vue";
import ExpenseTrackerCard from "./ExpenseTrackerCard/expenseTrackerCard.vue";
import InvestmentDashboardCard from "./InvestmentDashboardCard/investmentDashboardCard.vue";
import PermotionBanner from "./PermotionBanner/permotionBanner.vue";
import DividendCard from "./DividendCard/dividendCard.vue";
import AddFundModal from "./AddFundModal/addFundModal.vue";
import ExpenseAddVisitCard from "./ExpenseAddVisitCard/expenseAddVisitCard.vue";
import FilePreview from "./FilePreview/filePreview.vue";
import SummaryCard from "./SummaryCard/summaryCard.vue";
import SummaryMainCard from "./SummaryMainCard/summaryMainCard.vue";
import DepositeFundsCard from "./DepositeFundsCard/depositeFundsCard.vue";
import DividendSummaryCard from "./DividendSummaryCard/dividendSummaryCard.vue";
import WalletSummary from "./WalletSummary/walletSummary.vue";
import RegularPayment from "./RegularPayment/regularPayment.vue";
import PaymentSummary from "./PaymentSummary/paymentSummary.vue";
import ContributionDetailsCard from "./ContributionDetailsCard/contributionDetailsCard.vue";
import MultipleContributors from "./MultipleContributors/multipleContributors.vue";
import AddNewVisitModal from "./AddNewVisitModal/addNewVisitModal.vue";
import WithdrawFunds from "./WithdrawFunds/withdrawFunds.vue";
import InvestmentCard from "./InvestmentCard/investmentCard.vue";
import AddFundsCard from "./AddFundsCard/addFundsCard.vue";
import AddNewVisit from "./Modal/AddNewVisit.vue";
import ContributorsList from "./Modal/ContributorsListModal.vue";
import InviteMembersToPay from "./InviteMembersToPay/inviteMembersToPay.vue";
import InviteMemberPay from "./Modal/InviteMemberPay.vue";
import AddBillManualCard from "./AddBillManualCard/addBillManualCard.vue";
import DocumentUploadCard from "./DocumentUploadCard/documentUploadCard.vue";
import TransactionHistory from "./TransactionHistory/transactionHistory.vue";
import Loader from "./Loader/Loader.vue"
import TableComponent from "./TableComponent/TableComponent.vue";
import TableFilter from "./TableFilter/table-filter.vue";
import ContributionTransactionDetailsCard from "./ContributionTransactionDetailsCard/ContributionTransactionDetailsCard.vue";
import ContributorAvatar from './ContributorAvatar/ContributorAvatar.vue';

let components = {
  FormGroupInput,
  CapsuleField,
  CommonDocuments,
  InvitationMethod,
  ReuseableButton,
  AppTypography,
  CommonFooter,
  MainBanner,
  AppSelect,
  ProfileCard,
  LogoHeader,
  BackButton,
  ImageSvg,
  InsuranceQuestion,
  PremiumDetails,
  SuccessAlert,
  WelcomeInvite,
  MainFooter,
  PaymentMethod,
  ReusablePaymentMethod,
  DocType,
  BillerDetails,
  InviteFriendsAndFamily,
  TitleActionBar,
  DetailsCard,
  WalletHistory,
  ContributionListCard,
  ContributionTransactionDetailsCard,
  Dropdown,
  CommonTable,
  PaymentAnalyticsCard,
  ZyanazaCard,
  ExpenseTrackerCard,
  InvestmentDashboardCard,
  PermotionBanner,
  DividendCard,
  AddFundModal,
  ExpenseAddVisitCard,
  FilePreview,
  SummaryCard,
  SummaryMainCard,
  DepositeFundsCard,
  DividendSummaryCard,
  WalletSummary,
  RegularPayment,
  PaymentSummary,
  ContributionDetailsCard,
  MultipleContributors,
  AddNewVisitModal,
  WithdrawFunds,
  InvestmentCard,
  AddFundsCard,
  AddNewVisit,
  ContributorsList,
  InviteMembersToPay,
  InviteMemberPay,
  AddBillManualCard,
  DocumentUploadCard,
  TransactionHistory,
  Loader,
  TableComponent,
  TableFilter,
  ContributorAvatar,
  EditProfileCard,
  SecurityQuestions,
  AccountDetails,
  KYCDocuments
};

export default components;

export {
  AppSelect,
  AppTypography,
  CapsuleField,
  CommonDocuments,
  FormGroupInput,
  InvitationMethod,
  ReuseableButton,
  CommonFooter,
  MainBanner,
  ProfileCard,
  LogoHeader,
  BackButton,
  ImageSvg,
  InsuranceQuestion,
  PremiumDetails,
  SuccessAlert,
  WelcomeInvite,
  MainFooter,
  PaymentMethod,
  ReusablePaymentMethod,
  DocType,
  BillerDetails,
  InviteFriendsAndFamily,
  TitleActionBar,
  DetailsCard,
  WalletHistory,
  ContributionListCard,

  Dropdown,
  CommonTable,
  PaymentAnalyticsCard,
  ZyanazaCard,
  ExpenseTrackerCard,
  InvestmentDashboardCard,
  PermotionBanner,
  DividendCard,
  AddFundModal,
  ExpenseAddVisitCard,
  FilePreview,
  SummaryCard,
  SummaryMainCard,
  DepositeFundsCard,
  DividendSummaryCard,
  WalletSummary,
  RegularPayment,
  PaymentSummary,
  ContributionDetailsCard,
  MultipleContributors,
  AddNewVisitModal,
  WithdrawFunds,
  InvestmentCard,
  AddFundsCard,
  AddNewVisit,
  ContributorsList,
  InviteMembersToPay,
  InviteMemberPay,
  AddBillManualCard,
  DocumentUploadCard,
  TransactionHistory,
  Loader,
  TableComponent,
  ContributionTransactionDetailsCard,
  TableFilter,
  ContributorAvatar,
  EditProfileCard,
  SecurityQuestions,
  AccountDetails,
  KYCDocuments
};
