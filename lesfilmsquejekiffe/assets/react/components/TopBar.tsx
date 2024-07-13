import {
    AppBar,
    Box,
    FormControl,
    InputLabel,
    MenuItem,
    Select,
    SelectChangeEvent,
    Toolbar,
    Typography
} from "@mui/material";
import React, {ReactNode} from "react";
import {User} from "../types/User";

export type TopBarProps = {
    users: User[];
    onUserChange: (event: SelectChangeEvent, child: ReactNode) => void;
}
export default function TopBar(props: TopBarProps) {
    return <Box sx={{ flexGrow: 1 }}>
            <AppBar position="static">
            <Toolbar>
                <Typography component="h1" variant="h6" sx={{flexGrow: 1}}>Les Films Que Je Kiffe !</Typography>
                <FormControl variant="outlined" sx={{ m: 1, minWidth: 150 }}>
                    <InputLabel id="user-selector-label">Utilisateur :</InputLabel>
                    <Select labelId="user-selector-label"
                            onChange={props.onUserChange}>
                        {props.users.map((item) =>
                            <MenuItem value={item.id + "/" + item.name}>{item.name}</MenuItem>
                        )}
                    </Select>
                </FormControl>
            </Toolbar>
        </AppBar>
    </Box>
}
