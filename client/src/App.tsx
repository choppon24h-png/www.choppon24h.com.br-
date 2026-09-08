import { Toaster } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import NotFound from "@/pages/NotFound";
import { Route, Switch } from "wouter";
import ErrorBoundary from "./components/ErrorBoundary";
import { RouteHead } from "./components/RouteHead";
import { ThemeProvider } from "./contexts/ThemeContext";
import AdminLeads from "./pages/AdminLeads";
import Delivery from "./pages/Delivery";
import CustomerApp from "./pages/CustomerApp";
import Franchise from "./pages/Franchise";
import FranchisePortal from "./pages/FranchisePortal";
import Home from "./pages/Home";
import ThankYou from "./pages/ThankYou";

function Router() {
  // make sure to consider if you need authentication for certain routes
  return (
    <Switch>
      <Route path={"/"} component={Home} />
      <Route path={"/franquia"} component={Franchise} />
      <Route path={"/franquia/obrigado"} component={ThankYou} />
      <Route path={"/delivery"} component={Delivery} />
      <Route path={"/aplicativo"} component={CustomerApp} />
      <Route path={"/franqueado"} component={FranchisePortal} />
      <Route path={"/admin"} component={AdminLeads} />
      <Route path={"/404"} component={NotFound} />
      {/* Final fallback route */}
      <Route component={NotFound} />
    </Switch>
  );
}

// NOTE: About Theme
// - First choose a default theme according to your design style (dark or light bg), than change color palette in index.css
//   to keep consistent foreground/background color across components
// - If you want to make theme switchable, pass `switchable` ThemeProvider and use `useTheme` hook

function App() {
  return (
    <ErrorBoundary>
      <ThemeProvider
        defaultTheme="dark"
        // switchable
      >
        <TooltipProvider>
          <Toaster />
          <RouteHead />
          <Router />
        </TooltipProvider>
      </ThemeProvider>
    </ErrorBoundary>
  );
}

export default App;
