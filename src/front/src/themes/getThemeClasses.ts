import { ThemeColors } from "@/themes/themes";

export const getThemeClasses = (colors: ThemeColors) => ({
  backgroundMain: `bg-[${colors.backgroundMain}]`,
  backgroundSecondary: `bg-[${colors.backgroundSecondary}]`,
  textPrimary: `text-[${colors.textPrimary}]`,
  textSecondary: `text-[${colors.textSecondary}]`,
  textInverted: `text-[${colors.textInverted}]`,
  borderPrimary: `border-[${colors.borderPrimary}]`,
  primary: `text-[${colors.primary}]`,
  error: `text-[${colors.error}]`,
});
