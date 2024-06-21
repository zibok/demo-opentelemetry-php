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
                <Typography variant="h1" component="div" sx={{ flexGrow: 1 }}>Mate Ma Music !</Typography>
                <InputLabel id="user-selector-label">Choose a user</InputLabel>

                <FormControl variant="filled" sx={{ m: 1, minWidth: 120 }}>
                <Select labelId="user-selector-label"
                        label="User"
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
