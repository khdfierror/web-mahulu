<template>
    <DashboardIcon v-if="name === 'dashboard'" />
    <CashierIcon v-else-if="name === 'cashier'" />
    <VisitWebsiteIcon v-else-if="name === 'visit-website'" />
    <QuestionSurveyIcon v-else-if="name === 'survey-question'" />
    <AnswerSurveyIcon v-else-if="name === 'survey-answer'" />
    <GroupNavigationIcon v-else-if="name === 'navigation-group'" />
    <NavigationIcon v-else-if="name === 'navigation'" />
    <PageIcon v-else-if="name === 'page'" />
    <ContactIcon v-else-if="name === 'contact'" />
    <AreaIcon v-else-if="name === 'area'" />
    <NewsFlashIcon v-else-if="name === 'news-flash'" />
    <SliderIcon v-else-if="name === 'slider'" />
    <ProductIcon v-else-if="name === 'product'" />
    <CustomerIcon v-else-if="name === 'customer'" />
    <CategoryIcon v-else-if="name === 'category'" />
    <BrandIcon v-else-if="name === 'brand'" />
    <OrderIcon v-else-if="name === 'order'" />
    <DiscountIcon v-else-if="name === 'discount'" />
    <ReviewIcon v-else-if="name === 'review'" />
    <StockIcon v-else-if="name === 'stock'" />
    <StockInIcon v-else-if="name === 'stock-in'" />
    <StockOutIcon v-else-if="name === 'stock-out'" />
    <StockOpnameIcon v-else-if="name === 'stock-opname'" />
    <ProductionIcon v-else-if="name === 'production'" />
    <PurchaseOrderIcon v-else-if="name === 'purchase-order'" />
    <SupplierIcon v-else-if="name === 'supplier'" />
    <MutationIcon v-else-if="name === 'mutation'" />
    <SettingIcon v-else-if="name === 'setting'" />
    <OutletIcon v-else-if="name === 'outlet'" />
    <AccountIcon v-else-if="name === 'account'" />
    <ShippingCostIcon v-else-if="name === 'shipping-cost'" />
    <PostsIcon v-else-if="name === 'posts'" />
    <CategoriesIcon v-else-if="name === 'categories'" />
    <AuthorsIcon v-else-if="name === 'authors'" />
    <PaymentIcon v-else-if="name === 'payment'" />
    <PermitIcon v-else-if="name === 'services-permit'" />
    <ReportIcon v-else-if="name === 'services-report'" />
    <RequestDataIcon v-else-if="name === 'services-data-request'" />
    <CaseIcon v-else-if="name === 'services-case'" />
    <PermitCategories v-else-if="name === 'permit-categories'" />
    <CagarBudaya v-else-if="name === 'services-cagar-budaya'" />
    <WbsCategories v-else-if="name === 'wbs-categories'" />
    <WbsReport v-else-if="name === 'wbs-report'" />
    <GratifikasiPeristiwa v-else-if="name === 'gratifikasi-peristiwa'" />
    <GratifikasiJenis v-else-if="name === 'gratifikasi-jenis'" />
    <GratifikasiLaporan v-else-if="name === 'gratifikasi-laporan'" />
    <AgendaIcon v-else-if="name === 'agenda-icon'" />
    <BeritaIcon v-else-if="name === 'berita-icon'" />
    <AplIcon v-else-if="name === 'apl-icon'" />
    <DocumentIcon v-else-if="name === 'document-icon'" />
</template>

<script setup>
import DashboardIcon from '~icons/heroicons-outline/presentation-chart-line';
import CashierIcon from '~icons/heroicons-outline/shopping-cart';
import VisitWebsiteIcon from '~icons/heroicons-outline/external-link';

import QuestionSurveyIcon from '~icons/heroicons-outline/question-mark-circle';
import AnswerSurveyIcon from '~icons/tabler/info-circle';

import GroupNavigationIcon from '~icons/tabler/list-details';
import NavigationIcon from '~icons/tabler/directions';

import PageIcon from '~icons/tabler/file-invoice';
import AreaIcon from '~icons/tabler/map';
import ContactIcon from '~icons/tabler/message-report';
import NewsFlashIcon from '~icons/tabler/bolt';
import SliderIcon from '~icons/tabler/slideshow';

import ProductIcon from '~icons/heroicons-outline/view-grid';
import CustomerIcon from '~icons/heroicons-outline/user-group';
import CategoryIcon from '~icons/heroicons-outline/color-swatch';
import BrandIcon from '~icons/heroicons-outline/badge-check';
import OrderIcon from '~icons/heroicons-outline/shopping-bag';
import DiscountIcon from '~icons/heroicons-outline/receipt-tax';
import ReviewIcon from '~icons/heroicons-outline/star';
import PaymentIcon from '~icons/heroicons-outline/cash';

import StockIcon from '~icons/tabler/box';
import StockInIcon from '~icons/tabler/download';
import StockOutIcon from '~icons/tabler/upload';
import StockOpnameIcon from '~icons/tabler/checkup-list';
import ProductionIcon from '~icons/tabler/building-factory';
import PurchaseOrderIcon from '~icons/tabler/businessplan';
import SupplierIcon from '~icons/tabler/building';
import MutationIcon from '~icons/tabler/truck-delivery';

import SettingIcon from '~icons/tabler/settings';
import OutletIcon from '~icons/tabler/building-store';
import AccountIcon from '~icons/heroicons-outline/user-circle';
import ShippingCostIcon from '~icons/tabler/receipt-2';

import PermitIcon from '~icons/tabler/license';
import ReportIcon from '~icons/tabler/report-search';
import RequestDataIcon from '~icons/tabler/file-chart';
import CaseIcon from '~icons/tabler/flag';

import PostsIcon from '~icons/tabler/news';
import CategoriesIcon from '~icons/heroicons-outline/collection';
import AuthorsIcon from '~icons/heroicons-outline/users';

import PermitCategories from '~icons/tabler/category-2';
import CagarBudaya from '~icons/tabler/map-pins';
import WbsCategories from '~icons/tabler/category';
import WbsReport from '~icons/ri/alarm-warning-line';

import GratifikasiPeristiwa from '~icons/heroicons-outline/star';
import GratifikasiLaporan from '~icons/tabler/report-analytics';
import GratifikasiJenis from '~icons/ri/list-settings-line';

import AgendaIcon from '~icons/tabler/calendar-event';

import BeritaIcon from '~icons/tabler/article';

import AplIcon from '~icons/tabler/apps';

import DocumentIcon from '~icons/tabler/files';

defineProps({
    name: String,
});
</script>
